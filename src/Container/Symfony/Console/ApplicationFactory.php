<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Symfony\Console;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\Console\Command\CheckCommand;
use Ghostwriter\Compliance\Console\Command\MatrixCommand;
use Ghostwriter\Compliance\Console\Command\RunCommand;
use Ghostwriter\Compliance\Console\Command\WorkflowCommand;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Container\PsrContainer;
use Ghostwriter\GCS\Console\Command\Composer\ComposerBumpCommand;
use Ghostwriter\GCS\Console\Command\Composer\ComposerPostInstallCommand;
use Ghostwriter\GCS\Console\Command\Composer\ComposerPostUpdateCommand;
use Ghostwriter\GCS\Console\Command\Composer\ComposerUpdateCommand;
use Ghostwriter\GCS\Console\Command\ComposerRequireChecker\ComposerRequireCheckerCommand;
use Ghostwriter\GCS\Console\Command\ComposerUnused\ComposerUnusedCommand;
use Ghostwriter\GCS\Console\Command\ConfigureGhostwriterContainerDefinitionCommand;
use Ghostwriter\GCS\Console\Command\Ecs\EcsInitCommand;
use Ghostwriter\GCS\Console\Command\Git\Flow\Feature\GitFlowStartCommand;
use Ghostwriter\GCS\Console\Command\Git\Flow\GitFlowInitCommand;
use Ghostwriter\GCS\Console\Command\Git\GitMergeCommand;
use Ghostwriter\GCS\Console\Command\Git\GitMergeUpCommand;
use Ghostwriter\GCS\Console\Command\Git\GitPullCommand;
use Ghostwriter\GCS\Console\Command\Git\GitSetupCommand;
use Ghostwriter\GCS\Console\Command\Git\GitWipCommand;
use Ghostwriter\GCS\Console\Command\GitCleanupCommand;
use Ghostwriter\GCS\Console\Command\GitHub\Actions\DeleteAllWorkflowRunsCommand;
use Ghostwriter\GCS\Console\Command\GitHub\Actions\DeleteWorkflowRunsCommand;
use Ghostwriter\GCS\Console\Command\GitHub\DotGitHub\SettingYamlCommand;
use Ghostwriter\GCS\Console\Command\ImportRepositoriesCommand;
use Ghostwriter\GCS\Console\Command\Infection\InfectionRunCommand;
use Ghostwriter\GCS\Console\Command\Infection\InfectionUpdateConfigCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeAttributeCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeClassCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeCommandCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeConfigCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeDefinitionCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeEnumCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeEventCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeExceptionCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeExtensionCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeFactoryCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeInterfaceCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeListenerCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeProviderCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeTestCommand;
use Ghostwriter\GCS\Console\Command\Make\MakeTraitCommand;
use Ghostwriter\GCS\Console\Command\Nix\Flake\NixFlakeInitCommand;
use Ghostwriter\GCS\Console\Command\Nix\Flake\NixFlakeNewCommand;
use Ghostwriter\GCS\Console\Command\Nix\Flake\NixFlakeUpdateCommand;
use Ghostwriter\GCS\Console\Command\Phive\PhiveInstallCommand;
use Ghostwriter\GCS\Console\Command\Phive\PhiveUninstallCommand;
use Ghostwriter\GCS\Console\Command\Phive\PhiveUpdateCommand;
use Ghostwriter\GCS\Console\Command\PHPBench\PHPBenchCommand;
use Ghostwriter\GCS\Console\Command\PHPUnit\PHPUnitFormatCommand;
use Ghostwriter\GCS\Console\Command\PHPUnit\PHPUnitMigrateCommand;
use Ghostwriter\GCS\Console\Command\PHPUnit\PHPUnitTestCommand;
use Ghostwriter\GCS\Console\Command\Pip\PipCveCommand;
use Ghostwriter\GCS\Console\Command\Pip\PipUpdateCommand;
use Ghostwriter\GCS\Console\Command\Psalm\PsalmBaselineCommand;
use Ghostwriter\GCS\Console\Command\Psalm\PsalmCommand;
use Ghostwriter\GCS\Console\Command\Psalm\PsalmSecurityCommand;
use Ghostwriter\GCS\Console\Command\QueueStartCommand;
use Ghostwriter\GCS\Console\Command\Rector\RectorCreateRuleCommand;
use Ghostwriter\GCS\Console\Command\Rector\RectorInitCommand;
use Ghostwriter\GCS\Console\Command\ServeCommand;
use Override;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Throwable;

/**
 * @see ApplicationFactoryTest
 *
 * @implements FactoryInterface<Application>
 */
final readonly class ApplicationFactory implements FactoryInterface
{
    public const array COMMANDS = [
        'check' => CheckCommand::class,
        'matrix' => MatrixCommand::class,
        'run' => RunCommand::class,
        'workflow' => WorkflowCommand::class,
    ];

    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Application
    {
        $application = new Application(
            name: 'Compliance',
            version: InstalledVersions::getPrettyVersion('ghostwriter/compliance')
        );

        $application->setAutoExit(false);

        $application->setCatchErrors(false);

        $application->setCatchExceptions(false);

        $application->setCommandLoader(new ContainerCommandLoader(
            $container->get(PsrContainer::class),
            self::COMMANDS
        ));

        $application->setDefaultCommand('run');

        return $application;
    }
}
