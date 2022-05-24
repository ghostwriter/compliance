<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\OutputEvent;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OutputListener implements EventListenerInterface
{
    private SymfonyStyle $output;

    public function __construct(SymfonyStyle $output)
    {
        $this->output = $output;
    }

    public function __invoke(OutputEvent $event): void
    {
        $this->output->{$event->getType()}($event->getMessage());
    }
}
