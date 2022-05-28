<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Throwable;

final class CheckListener implements EventListenerInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(CheckEvent $checkEvent): void
    {
        /** @var string $job */
        $job = $checkEvent->getInput()
            ->getArgument('job');

        $checkEvent->getDispatcher()
            ->dispatch(new OutputEvent($job));
    }
}
