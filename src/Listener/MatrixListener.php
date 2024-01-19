<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Composer\Semver\Semver;
use Ghostwriter\Compliance\Enum\ComposerDependency;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Ghostwriter\Compliance\Service\Composer;
use Ghostwriter\Compliance\Service\Composer\Extension;
use Ghostwriter\Compliance\Service\ComposerCacheFilesDirectoryFinder;
use Ghostwriter\Compliance\Service\Job;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\ToolInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use const FILE_APPEND;
use const PHP_EOL;
use function array_map;
use function array_unique;
use function chdir;
use function dispatchOutputEvent;
use function file_put_contents;
use function getcwd;
use function iterator_to_array;
use function sprintf;
use function sys_get_temp_dir;
use function tempnam;

final readonly class MatrixListener implements EventListenerInterface
{
    public function __construct(
        // remove the damn container im using it to get the tools that were tagged
        private ContainerInterface $container,
        private Composer $composer,
        private ComposerCacheFilesDirectoryFinder $composerCacheFilesDirectoryFinder,
        private EnvironmentVariables $environmentVariables,
    ) {
    }

    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        $root = getcwd();

        if ($root === false) {
            throw new RuntimeException('Could not get current working directory');
        }

        chdir($root);

        $composerCacheFilesDirectory = '/home/runner/.cache/composer/files';

        //TODO: fix this ^^^^^

        // print_r([
        //     $composerCacheFilesDirectory,
        //     ($this->composerCacheFilesDirectoryFinder)()
        // ]);

        $composerJsonPath = $this->composer->getJsonFilePath($root);
        $composerLockPath = $this->composer->getLockFilePath($root);

        $composerJson = $this->composer->readJsonFile($root);

        $phpVersionConstraint = $composerJson->getPhpVersionConstraint();

        /** @var string $constraints */
        $constraints = $phpVersionConstraint->getVersion();

        $requiredPhpExtensions = array_map(
            static fn (Extension $extension) => (string) $extension,
            iterator_to_array($composerJson->getRequiredPhpExtensions())
        );

        foreach ($this->container->tagged(ToolInterface::class) as $tool) {
            if (! $tool->isPresent()) {
                continue;
            }

            $name = $tool->name();

            $command = $tool->command();

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
                        PhpVersion::latest(),
                    )
                );
                continue;
            }

            foreach (PhpVersion::cases() as $phpVersion) {
                $isPhpVersionExperimental = PhpVersion::isExperimental($phpVersion);
                if ($isPhpVersionExperimental) {
                    continue;
                }

                if (! Semver::satisfies($phpVersion->toString(), $constraints)) {
                    continue;
                }

                foreach (ComposerDependency::cases() as $composerDependency) {
                    $isComposerDependencyExperimental = ComposerDependency::isExperimental(
                        $composerDependency
                    );

                    foreach (OperatingSystem::cases() as $operatingSystem) {
                        $generateMatrixEvent->include(
                            Job::new(
                                $name,
                                $command,
                                $extensions,
                                $composerCacheFilesDirectory,
                                $composerJsonPath,
                                $composerLockPath,
                                $phpVersion,
                                $composerDependency,
                                $operatingSystem,
                                $isComposerDependencyExperimental,
                            )
                        );
                    }
                }
            }
        }

        $gitHubOutput = $this->environmentVariables->get('GITHUB_OUTPUT', tempnam(sys_get_temp_dir(), 'GITHUB_OUTPUT'));

        $matrix = sprintf('matrix=%s' . PHP_EOL, $generateMatrixEvent->getMatrix());

        file_put_contents($gitHubOutput, $matrix, FILE_APPEND);

        dispatchOutputEvent($matrix);
    }

    public function write(string $message): int
    {
        return dispatchOutputEvent($message)->isPropagationStopped() ? Command::FAILURE : Command::SUCCESS;
    }
}
