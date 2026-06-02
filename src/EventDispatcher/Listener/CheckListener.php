<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Listener;

use Ghostwriter\Compliance\EventDispatcher\Event\CheckEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\Interface\EventDispatcher\ListenerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Throwable;

final readonly class CheckListener implements ListenerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /** @throws Throwable */
    public function __invoke(CheckEvent $checkEvent): void
    {
        $this->eventDispatcher->dispatch(OutputEvent::new($checkEvent->input()->getArgument('job')));
    }
}
