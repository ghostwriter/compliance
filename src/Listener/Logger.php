<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function container;
use function dispatch;
use function sprintf;

final readonly class Logger implements EventListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle,
    ) {
    }

    /**
     * @param GitHubEventInterface<bool> $event
     */
    public function __invoke(GitHubEventInterface $event): void
    {
        $this->symfonyStyle->info('Event Class:</> <info>'.$event::class.'</info>');
        $this->symfonyStyle->info('Event Payload: '.$event->payload());


//        $this->symfonyStyle->info(sprintf(
//            '<fg=white;bg=black;options=bold>Event Class:</> <info>%s</info>',
//            $event::class
//        ));
//
//        $this->symfonyStyle->info(sprintf(
//            '<fg=white;bg=black;options=bold>Event Payload:</> <info>%s</info>',
//            $event->payload()
//        ));

        dispatch(container()->get(MatrixEvent::class));
    }
}
