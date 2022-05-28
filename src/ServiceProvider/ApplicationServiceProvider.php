<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Configuration\ComplianceConfiguration;
use Ghostwriter\Compliance\Event\ChangeWorkingDirectoryEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Throwable;
use const DIRECTORY_SEPARATOR;
use function file_exists;
use function getcwd;

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
    public function __construct(ContainerInterface $container)
    {
        /** @var class-string<ServiceProviderInterface> $provider */
        foreach (self::PROVIDERS as $provider) {
            $container->build($provider);
        }
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container): void
    {
        $complianceConfigPath = getcwd() . DIRECTORY_SEPARATOR . 'compliance.php';

        $dispatcher = $container->get(DispatcherInterface::class);

        $changeWorkingDirectoryEvent = $container->build(ChangeWorkingDirectoryEvent::class);

        $dispatcher->dispatch($changeWorkingDirectoryEvent);

        if (file_exists($complianceConfigPath)) {
            /** @var callable(ComplianceConfiguration) $config */
            $config = include $complianceConfigPath;
            $container->invoke($config);
            $dispatcher->dispatch(new OutputEvent('Found config path: ' . $complianceConfigPath));
        }
    }
}
