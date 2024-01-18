<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Interface\EventListenerInterface;

final readonly class CheckListener implements EventListenerInterface
{
    public function __invoke(CheckEvent $checkEvent): void
    {
        /** @var string $job */
        $job = $checkEvent->getInput()
            ->getArgument('job');

        $checkEvent->dispatch(new OutputEvent($job));
    }
}
