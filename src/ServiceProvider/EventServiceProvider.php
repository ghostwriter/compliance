<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Extension\ListenerProviderExtension;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\EventDispatcher;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;

final readonly class EventServiceProvider implements ServiceProviderInterface
{
    public function __invoke(ContainerInterface $container): void
    {
        $container->alias(EventDispatcherInterface::class, EventDispatcher::class);
        $container->alias(ListenerProviderInterface::class, ListenerProvider::class);
        $container->extend(ListenerProvider::class, ListenerProviderExtension::class);
    }
}
