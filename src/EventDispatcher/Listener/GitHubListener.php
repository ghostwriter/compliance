<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Listener;

use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\EventDispatcher\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Interface\EventDispatcher\ListenerInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Throwable;

final readonly class GitHubListener implements ListenerInterface
{
    public function __construct(
        private ContainerInterface $container,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /** @throws Throwable */
    public function __invoke(GitHubEventInterface $gitHubEvent): void
    {
        $this->eventDispatcher->dispatch($this->container->build(MatrixEvent::class));
    }
}
