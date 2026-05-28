<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Ghostwriter\EventDispatcher;

use Ghostwriter\Compliance\EventDispatcher\Event\CheckEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\CopyWorkflowEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHubEventInterface;
use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\EventDispatcher\Listener\CheckListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\CopyWorkflowListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\Debug;
use Ghostwriter\Compliance\EventDispatcher\Listener\GitHubListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\MatrixListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\OutputListener;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
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
    public const array LISTEN = [
        'object' => [
            //             Debug::class,
            //             Logger::class
        ],
        CheckEvent::class => [CheckListener::class],
        MatrixEvent::class => [MatrixListener::class],
        GitHubEventInterface::class => [GitHubListener::class],
        OutputEvent::class => [OutputListener::class],
        CopyWorkflowEvent::class => [Debug::class, CopyWorkflowListener::class],
    ];

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

        $listen = self::LISTEN;

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
