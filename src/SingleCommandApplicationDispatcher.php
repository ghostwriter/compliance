<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\GitHub\GitHubCreateEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubDeleteEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubDeploymentEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubDeploymentStatusEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubForkEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubGollumEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubIssueCommentEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubIssuesEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubLabelEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubMergeGroupEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubMilestoneEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPageBuildEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubProjectCardEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubProjectColumnEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubProjectEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPublicEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPullRequestCommentEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPullRequestEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPullRequestReviewCommentEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPullRequestReviewEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPullRequestTargetEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubPushEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubRegistryPackageEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubReleaseEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubRepositoryDispatchEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubScheduleEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubStatusEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWatchEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowCallEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowDispatchEvent;
use Ghostwriter\Compliance\Event\GitHub\GitHubWorkflowRunEvent;
use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ghostwriter\Compliance\Option\Filesystem;

final readonly class SingleCommandApplicationDispatcher
{
    public function __construct(
        private DispatcherInterface $dispatcher,
        private Filesystem $filesystem,
    ) {
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output
    ): int {

        $output->writeln(sprintf(
            Compliance::LOGO,
            Compliance::BLACK_LIVES_MATTER,
            Compliance::NAME,
        ));

        $eventName = $input->getArgument('event');

        $output->writeln(sprintf('GitHub Event Name: <info>%s</info>', $eventName));

        $payload = $input->getArgument('payload');

        if ($this->filesystem->missing($payload)) {
            $output->writeln(sprintf('GitHub Payload: <comment>%s File does not exist.</comment>', $payload));

            return Command::SUCCESS;
        }

        $content = $this->filesystem->read($payload);

        $output->writeln(sprintf('GitHub Payload File Content: %s<info>%s</info>', PHP_EOL, $content));

        return $this->dispatcher
            ->dispatch(
                match($eventName) {
                    default => throw new RuntimeException(sprintf('<comment>GitHub Event "%s" is not Supported.</comment>', $eventName)),
                    'create' => new GitHubCreateEvent($content),
                    'delete' => new GitHubDeleteEvent($content),
                    'deployment_status' => new GitHubDeploymentStatusEvent($content),
                    'deployment' => new GitHubDeploymentEvent($content),
                    'fork' => new GitHubForkEvent($content),
                    'gollum' => new GitHubGollumEvent($content),
                    'issue_comment' => new GitHubIssueCommentEvent($content),
                    'issues' => new GitHubIssuesEvent($content),
                    'label' => new GitHubLabelEvent($content),
                    'merge_group' => new GitHubMergeGroupEvent($content),
                    'milestone' => new GitHubMilestoneEvent($content),
                    'page_build' => new GitHubPageBuildEvent($content),
                    'project_card' => new GitHubProjectCardEvent($content),
                    'project_column' => new GitHubProjectColumnEvent($content),
                    'project' => new GitHubProjectEvent($content),
                    'public' => new GitHubPublicEvent($content),
                    'pull_request_comment' => new GitHubPullRequestCommentEvent($content),
                    'pull_request_review_comment' => new GitHubPullRequestReviewCommentEvent($content),
                    'pull_request_review' => new GitHubPullRequestReviewEvent($content),
                    'pull_request_target' => new GitHubPullRequestTargetEvent($content),
                    'pull_request' => new GitHubPullRequestEvent($content),
                    'push' => new GitHubPushEvent($content),
                    'registry_package' => new GitHubRegistryPackageEvent($content),
                    'release' => new GitHubReleaseEvent($content),
                    'repository_dispatch' => new GitHubRepositoryDispatchEvent($content),
                    'schedule' => new GitHubScheduleEvent($content),
                    'status' => new GitHubStatusEvent($content),
                    'watch' => new GitHubWatchEvent($content),
                    'workflow_call' => new GitHubWorkflowCallEvent($content),
                    'workflow_dispatch' => new GitHubWorkflowDispatchEvent($content),
                    'workflow_run' => new GitHubWorkflowRunEvent($content),
                }
            )
            ->isPropagationStopped() ? Command::FAILURE : Command::SUCCESS;
    }
}
