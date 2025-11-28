<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Ghostwriter\EventDispatcher;

use Ghostwriter\Compliance\EventDispatcher\Listener\Debug;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Override;
use Throwable;

/**
 * Registers class-based listeners defined in config/ghostwriter/event-dispatcher.php
 * into the ListenerProvider at boot time.
 *
 * Expected config shape:
 * return [
 *   'listen' => [ Event::class => [ ListenerClass::class, ... ], ... ]
 * ];
 */
final readonly class ListenerProviderExtension implements ExtensionInterface
{
    /**
     * @param ListenerProviderInterface $service
     *
     * @throws Throwable
     */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): void
    {
        if (! $service instanceof ListenerProviderInterface) {
            return;
        }

        $listen = $container->get(ConfigurationInterface::class)->get('ghostwriter.event-dispatcher.listen', []);

        if ($container->get(EnvironmentVariables::class)->get('GITHUB_DEBUG', '0') === '1') {
            $listen['object'][] = Debug::class;
        }

        foreach ($listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $service->listen($event, $listener);
            }
        }
    }
}
