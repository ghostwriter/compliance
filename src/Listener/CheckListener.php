<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Throwable;

final readonly class CheckListener implements EventListenerInterface
{
    private function __construct(
        private readonly DispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(CheckEvent $checkEvent): void
    {
        /** @var string $job */
        $job = $checkEvent->getInput()->getArgument('job');

        $this->dispatcher->dispatch(new OutputEvent($job));
    }
}
