<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Listener;

use Composer\Semver\Semver;
use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Composer\ComposerManager;
use Ghostwriter\Compliance\Composer\Extension;
use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Compliance\Enum\Tool;
use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\GitHub\Action\Job;
use Ghostwriter\Compliance\Interface\EventDispatcher\ListenerInterface;
use Ghostwriter\Compliance\Interface\ToolInterface;
use Ghostwriter\Compliance\Shell\ComposerCacheFilesDirectoryFinder;
use Ghostwriter\Compliance\Tool\Infection;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\Tool\Psalm;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Throwable;

use const FILE_APPEND;
use const PHP_EOL;

use function array_map;
use function array_unique;
use function chdir;
use function file_put_contents;
use function iterator_to_array;
use function sprintf;
use function sys_get_temp_dir;
use function tempnam;

final readonly class MatrixListener implements ListenerInterface
{
    public function __construct(
        private Automation $automation,
        // TODO: remove the damn container im using it to get the tools that were tagged
        private ContainerInterface $container,
        private ComposerManager $composerManager,
        private ComposerCacheFilesDirectoryFinder $composerCacheFilesDirectoryFinder,
        private EnvironmentVariables $environmentVariables,
        private FilesystemInterface $filesystem,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /** @throws Throwable */
    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        $currentWorkingDirectory = $this->filesystem->currentWorkingDirectory();

        chdir($currentWorkingDirectory);

        try {
            $composerJson = $this->composerManager->readJsonFile($currentWorkingDirectory);
        } catch (Throwable) {
            $this->publish($generateMatrixEvent);

            return;
        }

        $requiredPhpExtensions = array_map(
            static fn (Extension $extension): string => (string) $extension,
            iterator_to_array($composerJson->getRequiredPhpExtensions())
        );

        $composerStrategies = [];
        $operatingSystems = [];
        $phpVersions = [];
        $tools = [];

        foreach ($this->automation->toArray() as $automation) {
            if ($automation instanceof ComposerStrategy) {
                $composerStrategies[$automation->name] = $automation;

                continue;
            }

            if ($automation instanceof Tool) {
                $tools[$automation->name] = $automation;

                continue;
            }

            if ($automation instanceof PhpVersion) {
                $phpVersions[$automation->name] = $automation;

                continue;
            }

            if (! $automation instanceof OperatingSystem) {
                continue;
            }

            $operatingSystems[$automation->name] = $automation;
        }

        $constraints = $composerJson->getPhpVersionConstraint()->getVersion();
        $composerCacheFilesDirectory = ($this->composerCacheFilesDirectoryFinder)();
        $composerJsonPath = $this->composerManager->getJsonFilePath($currentWorkingDirectory);
        $composerLockPath = $this->composerManager->getLockFilePath($currentWorkingDirectory);
        foreach ($tools as $toolEnum) {
            $tool = $this->container->get($toolEnum->toString());
            if (! $tool instanceof ToolInterface) {
                continue;
            }

            if (! $tool->isPresent()) {
                continue;
            }

            $name = $tool->name();

            $command = $tool->command();

            /** @var list<string> $extensions */
            $extensions = array_unique([...$requiredPhpExtensions, ...$tool->extensions()]);

            if (! $tool instanceof PHPUnit) {
                $generateMatrixEvent->include(
                    Job::new(
                        $name,
                        $command,
                        $extensions,
                        $composerCacheFilesDirectory,
                        $composerJsonPath,
                        $composerLockPath,
                        $tool instanceof Psalm ? PhpVersion::PHP_84 : PhpVersion::latest(),
                        ComposerStrategy::LOCKED,
                        OperatingSystem::UBUNTU,
                        $tool instanceof Infection, // infection tool is experimental
                    )
                );

                continue;
            }

            foreach ($phpVersions as $phpVersion) {
                $isPhpVersionExperimental = PhpVersion::isExperimental($phpVersion);
                //                if ($isPhpVersionExperimental) {
                //                    continue;
                //                }

                if (! Semver::satisfies($phpVersion->toString(), $constraints)) {
                    continue;
                }

                foreach ($composerStrategies as $composerStrategy) {
                    if ($tool instanceof Psalm && ! $composerStrategy->isLocked()) {
                        continue;
                    }

                    $isComposerDependencyLowest = $composerStrategy->isLowest();
                    foreach ($operatingSystems as $operatingSystem) {
                        $isNotUbuntu = ! $operatingSystem->isUbuntu();

                        if ($tool instanceof Psalm && $isNotUbuntu) {
                            continue;
                        }

                        $generateMatrixEvent->include(
                            Job::new(
                                $name,
                                $command,
                                $extensions,
                                $composerCacheFilesDirectory,
                                $composerJsonPath,
                                $composerLockPath,
                                $phpVersion,
                                $composerStrategy,
                                $operatingSystem,
                                $isPhpVersionExperimental || $isComposerDependencyLowest || $isNotUbuntu,
                            )
                        );
                    }
                }
            }
        }

        $this->publish($generateMatrixEvent);
    }

    /** @throws Throwable */
    private function dispatchOutputEvent(string $message): void
    {
        $this->eventDispatcher->dispatch(OutputEvent::new([
            '::echo::on',
            sprintf('::group::%s - %s - %s', Compliance::NAME, Compliance::DESCRIPTION, Compliance::BLACK_LIVES_MATTER),
            $message,
            '::endgroup::',
            '::echo::off',
        ]));
    }

    /** @throws Throwable */
    private function publish(MatrixEvent $generateMatrixEvent): void
    {
        $gitHubOutput = $this->environmentVariables->get(
            'GITHUB_OUTPUT',
            tempnam(sys_get_temp_dir(), 'GITHUB_OUTPUT')
        );

        $matrix = sprintf('matrix=%s' . PHP_EOL, $generateMatrixEvent->getMatrix());

        file_put_contents($gitHubOutput, $matrix, FILE_APPEND);
        // $this->filesystem->append($gitHubOutput, $matrix);

        $this->dispatchOutputEvent($matrix);
    }
}
