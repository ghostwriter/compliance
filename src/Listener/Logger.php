<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Event\GitHub\GitHubPullRequestEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPushEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubScheduleEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowCallEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowDispatchEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowRunEvent;
use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function container;
use function dispatch;

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
        $this->symfonyStyle->info('Event Class: ' . $event::class);
        $this->symfonyStyle->info('Event Payload: ' . $event->payload());

        $stop = match (true) {
            //            $event instanceof GitHubPullRequestEvent,
            $event instanceof GitHubWorkflowCallEvent,
            $event instanceof GitHubWorkflowDispatchEvent,
            $event instanceof GitHubScheduleEvent,
            $event instanceof GitHubWorkflowRunEvent,
            $event instanceof GitHubPushEvent => false,
            default => true,
        };

        if ($stop) {
            return;
        }

        dispatch(container()->get(MatrixEvent::class));

        //        $this->symfonyStyle->info(sprintf(
        //            '<fg=white;bg=black;options=bold>Event Class:</> <info>%s</info>',
        //            $event::class
        //        ));
        //
        //        $this->symfonyStyle->info(sprintf(
        //            '<fg=white;bg=black;options=bold>Event Payload:</> <info>%s</info>',
        //            $event->payload()
        //        ));
    }
}
