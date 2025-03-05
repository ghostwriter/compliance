<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Console\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\EventDispatcher\Event\CopyWorkflowEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubBranchProtectionRuleEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubCheckRunEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubCheckSuiteEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubCreateEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubDeleteEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubDeploymentEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubDeploymentStatusEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubDiscussionCommentEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubDiscussionEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubForkEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubGollumEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubIssueCommentEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubIssuesEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubLabelEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubMergeGroupEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubMilestoneEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPageBuildEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubProjectCardEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubProjectColumnEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubProjectEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPublicEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPullRequestCommentEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPullRequestEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPullRequestReviewCommentEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPullRequestReviewEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPullRequestTargetEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubPushEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubRegistryPackageEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubReleaseEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubRepositoryDispatchEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubScheduleEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubStatusEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWatchEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWorkflowCallEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWorkflowDispatchEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHub\GitHubWorkflowRunEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Throwable;

use const PHP_EOL;

use function sprintf;

final class RunCommand extends Command
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly FilesystemInterface $filesystem,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EnvironmentVariables $environmentVariables,
        private readonly StyleInterface $symfonyStyle,
    ) {
        parent::__construct('run');
    }

    #[Override]
    protected function configure(): void
    {
        $this->setDescription('Generates a Job matrix for Github Actions.');

        // 'compliance.command.matrix'
        $this->addArgument(
            'event',
            InputArgument::OPTIONAL,
            'The name of the event that triggered the workflow.',
            $this->environmentVariables->get('GITHUB_EVENT_NAME', 'compliance.command.matrix')
        );

        // '/github/workflow/event.json'
        $this->addArgument(
            'payload',
            InputArgument::OPTIONAL,
            'The path to the file on the runner that contains the full event webhook payload.',
            $this->environmentVariables->get('GITHUB_EVENT_PATH', 'tests/Fixture/payload.json')
        );

        $this->addOption(
            'debug',
            'd',
            InputOption::VALUE_REQUIRED,
            'Enable debugging or verbose logging in job steps (one of "0" or "1").',
            $this->environmentVariables->get('RUNNER_DEBUG', '0')
        );

        $this->addArgument(
            'workspace',
            InputArgument::OPTIONAL,
            'The default working directory on the GitHub runner.',
            $this->environmentVariables->get('GITHUB_WORKSPACE', $this->filesystem->currentWorkingDirectory())
        );

        // GITHUB_ENV	The path on the runner to the file that sets variables from workflow commands.
        // This file is unique to the current step and changes for each step in a job.
        // For example, /home/runner/work/_temp/_runner_file_commands/set_env_87406d6e-4979-4d42-98e1-3dab1f48b13a.
        // For more information, see "Workflow commands for GitHub Actions."
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->writeln(sprintf(Compliance::LOGO, Compliance::BLACK_LIVES_MATTER, ''));

        $this->symfonyStyle->title(Compliance::NAME);

        $payload = (new SplFileInfo($input->getArgument('payload')))->getRealPath();
        if (false === $payload) {
            $output->writeln(
                sprintf('GitHub Payload: <error>%s File does not exist.</error>', $input->getArgument('payload'))
            );

            return Command::INVALID;
        }

        $eventName = $input->getArgument('event');

        $this->symfonyStyle->info(sprintf('GitHub Event: <comment>%s</comment>', $eventName));

        try {
            $this->eventDispatcher
                ->dispatch(
                    match ($eventName) {
                        default => throw new RuntimeException(sprintf(
                            '<comment>GitHub Event "%s" is not Supported.</comment>',
                            $eventName
                        )),
                        'branch_protection_rule' => new GitHubBranchProtectionRuleEvent($payload),
                        'check_run' => new GitHubCheckRunEvent($payload),
                        'check_suite' => new GitHubCheckSuiteEvent($payload),
                        'compliance.command.matrix' => $this->container->get(MatrixEvent::class),
                        'compliance.command.workflow' => $this->container->get(CopyWorkflowEvent::class),
                        'create' => new GitHubCreateEvent($payload),
                        'delete' => new GitHubDeleteEvent($payload),
                        'deployment' => new GitHubDeploymentEvent($payload),
                        'deployment_status' => new GitHubDeploymentStatusEvent($payload),
                        'discussion' => new GitHubDiscussionEvent($payload),
                        'discussion_comment' => new GitHubDiscussionCommentEvent($payload),
                        'fork' => new GitHubForkEvent($payload),
                        'gollum' => new GitHubGollumEvent($payload),
                        'issue_comment' => new GitHubIssueCommentEvent($payload),
                        'issues' => new GitHubIssuesEvent($payload),
                        'label' => new GitHubLabelEvent($payload),
                        'merge_group' => new GitHubMergeGroupEvent($payload),
                        'milestone' => new GitHubMilestoneEvent($payload),
                        'page_build' => new GitHubPageBuildEvent($payload),
                        'project' => new GitHubProjectEvent($payload),
                        'project_card' => new GitHubProjectCardEvent($payload),
                        'project_column' => new GitHubProjectColumnEvent($payload),
                        'public' => new GitHubPublicEvent($payload),
                        'pull_request' => new GitHubPullRequestEvent($payload),
                        'pull_request_comment' => new GitHubPullRequestCommentEvent($payload),
                        'pull_request_review' => new GitHubPullRequestReviewEvent($payload),
                        'pull_request_review_comment' => new GitHubPullRequestReviewCommentEvent($payload),
                        'pull_request_target' => new GitHubPullRequestTargetEvent($payload),
                        'push' => new GitHubPushEvent($payload),
                        'registry_package' => new GitHubRegistryPackageEvent($payload),
                        'release' => new GitHubReleaseEvent($payload),
                        'repository_dispatch' => new GitHubRepositoryDispatchEvent($payload),
                        'schedule' => new GitHubScheduleEvent($payload),
                        'status' => new GitHubStatusEvent($payload),
                        'watch' => new GitHubWatchEvent($payload),
                        'workflow_call' => new GitHubWorkflowCallEvent($payload),
                        'workflow_dispatch' => new GitHubWorkflowDispatchEvent($payload),
                        'workflow_run' => new GitHubWorkflowRunEvent($payload),
                    }
                );
        } catch (Throwable $throwable) {
            $this->symfonyStyle->error(
                sprintf(
                    '[%s] %s%s%s' . PHP_EOL,
                    $throwable::class,
                    $throwable->getMessage(),
                    PHP_EOL . PHP_EOL,
                    $throwable->getTraceAsString(),
                )
            );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
