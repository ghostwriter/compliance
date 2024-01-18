<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\EnvironmentVariables;
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
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Event\WorkflowEvent;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use function dirname;
use function getcwd;
use function sprintf;

final class RunCommand extends Command
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly DispatcherInterface $dispatcher,
        private readonly EnvironmentVariables $environmentVariables,
        private readonly StyleInterface $symfonyStyle,
    ) {
        parent::__construct('run');
    }

    protected function configure(): void
    {
        $this->setDescription('Generates a Job matrix for Github Actions.');
        $this->addArgument(
            'event',
            InputArgument::OPTIONAL,
            'The name of the event that triggered the workflow.',
            $this->environmentVariables->get('GITHUB_EVENT_NAME', 'compliance.command.matrix')
        );

        $this->addArgument(
            'workspace',
            InputArgument::OPTIONAL,
            'The default working directory on the GitHub runner.',
            $this->environmentVariables->get('GITHUB_WORKSPACE', getcwd())
        );

        $this->addArgument(
            'payload',
            InputArgument::OPTIONAL,
            'The path to the file on the runner that contains the full event webhook payload.',
            $this->environmentVariables->has('CI') ?
                $this->environmentVariables->get('GITHUB_EVENT_PATH', '/github/workflow/event.json') :
                $this->environmentVariables->get(
                    'GITHUB_EVENT_PATH',
                    dirname(__DIR__, 2) . '/tests/Fixture/payload.json'
                )
        );

        $this->addOption(
            'debug',
            'd',
            InputOption::VALUE_REQUIRED,
            'Enable debugging or verbose logging in job steps (one of "0" or "1").',
            $this->environmentVariables->get('RUNNER_DEBUG', '0')
        );
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->writeln(sprintf(Compliance::LOGO, Compliance::BLACK_LIVES_MATTER, ''));

        $this->symfonyStyle->title(Compliance::NAME);

        $payload = (new SplFileInfo($input->getArgument('payload')))->getRealPath();
        if ($payload === false) {
            $output->writeln(sprintf('GitHub Payload: <error>%s File does not exist.</error>', $payload));

            return Command::INVALID;
        }

        $eventName = $input->getArgument('event');

        return $this->dispatcher
            ->dispatch(
                match ($eventName) {
                    default => throw new RuntimeException(sprintf(
                        '<comment>GitHub Event "%s" is not Supported.</comment>',
                        $eventName
                    )),
                    'compliance.command.matrix' => $this->container->get(MatrixEvent::class),
                    'compliance.command.workflow' => $this->container->get(WorkflowEvent::class),
                    'create' => new GitHubCreateEvent($payload),
                    'delete' => new GitHubDeleteEvent($payload),
                    'deployment_status' => new GitHubDeploymentStatusEvent($payload),
                    'deployment' => new GitHubDeploymentEvent($payload),
                    'fork' => new GitHubForkEvent($payload),
                    'gollum' => new GitHubGollumEvent($payload),
                    'issue_comment' => new GitHubIssueCommentEvent($payload),
                    'issues' => new GitHubIssuesEvent($payload),
                    'label' => new GitHubLabelEvent($payload),
                    'merge_group' => new GitHubMergeGroupEvent($payload),
                    'milestone' => new GitHubMilestoneEvent($payload),
                    'page_build' => new GitHubPageBuildEvent($payload),
                    'project_card' => new GitHubProjectCardEvent($payload),
                    'project_column' => new GitHubProjectColumnEvent($payload),
                    'project' => new GitHubProjectEvent($payload),
                    'public' => new GitHubPublicEvent($payload),
                    'pull_request_comment' => new GitHubPullRequestCommentEvent($payload),
                    'pull_request_review_comment' => new GitHubPullRequestReviewCommentEvent($payload),
                    'pull_request_review' => new GitHubPullRequestReviewEvent($payload),
                    'pull_request_target' => new GitHubPullRequestTargetEvent($payload),
                    'pull_request' => new GitHubPullRequestEvent($payload),
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
            )
            ->isPropagationStopped() ? Command::FAILURE : Command::SUCCESS;
    }
}
