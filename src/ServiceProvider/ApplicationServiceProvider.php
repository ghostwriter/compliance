<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Extension\ComplianceExtension;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use Throwable;

final readonly class ApplicationServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container): void
    {
        $container->provide(ConsoleServiceProvider::class);
        $container->provide(EventServiceProvider::class);
        $container->provide(ConfigServiceProvider::class);
        $container->provide(MatrixServiceProvider::class);
    }
}
