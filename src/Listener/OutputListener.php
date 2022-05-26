<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\OutputEvent;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OutputListener implements EventListenerInterface
{
    public function __construct(private SymfonyStyle $output)
    {
    }

    public function __invoke(OutputEvent $event): void
    {
        $this->output->writeln(['::echo::on', $event->getMessage(), '::echo::off']);
    }
}
