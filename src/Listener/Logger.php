<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function sprintf;

final readonly class Logger implements EventListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle
    ) {
    }

    /**
     * @param GitHubEventInterface<bool> $event
     */
    public function __invoke(GitHubEventInterface $event): void
    {
        $this->symfonyStyle->info(sprintf(
            '<fg=white;bg=black;options=bold>Event Class:</> <info>%s</info>',
            $event::class
        ));

        $this->symfonyStyle->info(sprintf(
            '<fg=white;bg=black;options=bold>Event Payload:</> <info>%s</info>',
            $event->payload()
        ));
    }
}