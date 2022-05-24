<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

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
    private array $providers = [EventServiceProvider::class, ConsoleServiceProvider::class];

    /**
     * @throws Throwable
     */
    public function __construct(ContainerInterface $container)
    {
        /** @var class-string<ServiceProviderInterface> $provider */
        foreach ($this->providers as $provider) {
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
            $dispatcher->dispatch(new OutputEvent('Found config path: ' . $complianceConfigPath));
        }
    }
}
