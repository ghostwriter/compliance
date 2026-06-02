<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Listener;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPushEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubScheduleEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWorkflowCallEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWorkflowDispatchEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWorkflowRunEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\EventDispatcher\Event\GitHubEventInterface;
use Ghostwriter\Compliance\Interface\EventDispatcher\ListenerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class Logger implements ListenerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private SymfonyStyle $symfonyStyle,
        private EnvironmentVariables $environmentVariables,
    ) {}

    public function __invoke(GitHubEventInterface $gitHubEvent): void
    {
        if ($this->environmentVariables->get('GITHUB_DEBUG', '0') !== '1') {
            return;
        }

        $this->symfonyStyle->info('Event Class: ' . $gitHubEvent::class);
        $this->symfonyStyle->info('Event Payload: ' . $gitHubEvent->payload());

        $stop = match (true) {
            //            $event instanceof GitHubPullRequestEvent,
            $gitHubEvent instanceof GitHubWorkflowCallEvent,
            $gitHubEvent instanceof GitHubWorkflowDispatchEvent,
            $gitHubEvent instanceof GitHubScheduleEvent,
            $gitHubEvent instanceof GitHubWorkflowRunEvent,
            $gitHubEvent instanceof GitHubPushEvent => false,
            default => true,
        };

        if ($stop) {
            return;
        }

        $this->eventDispatcher->dispatch(MatrixEvent::new());

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
