<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Listener;

use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\Interface\EventDispatcher\ListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class OutputListener implements ListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle,
    ) {}

    public function __invoke(OutputEvent $outputEvent): void
    {
        $this->symfonyStyle->writeln($outputEvent->getMessage());
    }
}
