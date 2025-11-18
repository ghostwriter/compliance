<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Service\Extension\Ghostwriter\Config;

use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Config\Configuration;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Override;
use Throwable;
use function chdir;
use function error;
use function error_get_last;
use function sprintf;

/**
 * @see ConfigurationExtensionTest
 *
 * @implements ExtensionInterface<Configuration>
 */
final readonly class ConfigurationExtension implements ExtensionInterface
{
    public function __construct(
        private EnvironmentVariables $environmentVariables,
    ) {}

    /**
     * Returns the provided service, unmodified.
     *
     * @param ContainerInterface $container the container instance
     * @param Configuration      $service   the service instance to be extended
     *
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): void
    {
        dump([
            'file' => __FILE__,
            'line' => __LINE__,
            'message' => 'ConfigurationExtension invoked',
        ]);
        $currentWorkingDirectory = $this->environmentVariables->get('GITHUB_WORKSPACE');

        $result = chdir($currentWorkingDirectory);
        if (false === $result) {
            error(
                sprintf(
                    'Unable to change current working directory; %s; "%s" given.',
                    error_get_last()['message'] ?? 'No such file or directory',
                    $currentWorkingDirectory
                ),
                __FILE__,
                __LINE__
            );
        }

        // $service->set(Compliance::CURRENT_WORKING_DIRECTORY, $currentWorkingDirectory);

        // $complianceWorkflowTemplate = $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'src/automation.yml.dist';
        // if (file_exists($complianceWorkflowTemplate)) {
        //     $service->set(Compliance::WORKFLOW_TEMPLATE, realpath($complianceWorkflowTemplate));
        // }

//        return $service;
    }
}
