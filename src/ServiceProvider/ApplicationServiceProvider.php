<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Configuration\ComplianceConfiguration;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Throwable;
use const DIRECTORY_SEPARATOR;
use function chdir;
use function error_get_last;
use function file_exists;
use function getcwd;
use function getenv;
use function realpath;
use function sprintf;

final class ApplicationServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array<class-string<ServiceProviderInterface>>
     */
    private const PROVIDERS = [
        ConsoleServiceProvider::class,
        EventServiceProvider::class,
        MatrixServiceProvider::class,
    ];

    /**
     * @throws Throwable
     */
    public function __construct(private ContainerInterface $container)
    {
        foreach (self::PROVIDERS as $provider) {
            $this->container->register($provider);
        }
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container): void
    {
        $currentWorkingDirectory = getenv('GITHUB_WORKSPACE') ?: getcwd() ?: __DIR__;

        $result = @chdir($currentWorkingDirectory);

        if (false === $result) {
            $container->get(DispatcherInterface::class)
                ->dispatch(new OutputEvent(sprintf(
                    'Unable to change current working directory; %s; "%s" given.',
                    error_get_last()['message'] ?? 'No such file or directory',
                    $currentWorkingDirectory
                )));
        }

        $container->set(Compliance::CURRENT_WORKING_DIRECTORY, $currentWorkingDirectory);

        $complianceConfigPath = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'compliance.php';
        if (file_exists($complianceConfigPath)) {
            $container->set(Compliance::PATH_CONFIG, $complianceConfigPath);

            /** @var callable(ComplianceConfiguration):void $config */
            $config = require $complianceConfigPath;

            $container->invoke($config);
        }

        $complianceConfigTemplate = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'src/compliance.php.dist';
        if (file_exists($complianceConfigTemplate)) {
            $container->set(Compliance::TEMPLATE_CONFIG, realpath($complianceConfigTemplate));
        }

        $complianceWorkflowTemplate = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'src/compliance.yml.dist';
        if (file_exists($complianceWorkflowTemplate)) {
            $container->set(Compliance::TEMPLATE_WORKFLOW, realpath($complianceWorkflowTemplate));
        }
    }
}
