<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class Debug implements EventListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle
    ) {
    }
    /**
     * @param EventInterface<bool> $event
     */
    public function __invoke(EventInterface $event): void
    {
        $this->symfonyStyle->section($event::class);
    }
}
