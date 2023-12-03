<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Extension\ListenerProviderExtension;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExceptionInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Ghostwriter\EventDispatcher\Dispatcher;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Throwable;

final readonly class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container): void
    {
        $container->alias(DispatcherInterface::class, Dispatcher::class);
        $container->alias(ListenerProviderInterface::class, ListenerProvider::class);
        $container->extend(ListenerProvider::class, ListenerProviderExtension::class);
    }
}
