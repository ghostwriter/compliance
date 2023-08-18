<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Debug implements EventListenerInterface
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
    }

    public function __invoke(object $event): void
    {
        $this->symfonyStyle->section($event::class);
    }
}
