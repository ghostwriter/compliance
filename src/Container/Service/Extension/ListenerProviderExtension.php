<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Service\Extension;

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
use Ghostwriter\EventDispatcher\Interface\ExceptionInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Override;
use function array_key_exists;
use function spl_object_id;

/**
 * @implements ExtensionInterface<ListenerProvider>
 */
final readonly class ListenerProviderExtension implements ExtensionInterface
{
    private const array EVENTS = [
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

    public function __construct(
        private EnvironmentVariables $environmentVariables,
    ) {}

    /**
     * @param ListenerProvider $service
     *
     * @throws ExceptionInterface
     */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): void
    {
        $events = self::EVENTS;

        if ($this->environmentVariables->get('GITHUB_DEBUG', '0') === '1') {
            $events['object'][] = Debug::class;
        }

        foreach ($events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $service->bind($event, $listener);
            }
        }
    }
}
