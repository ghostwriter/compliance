<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Composer\Semver\Semver;
use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\AbstractEvent;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Option\ComposerDependency;
use Ghostwriter\Compliance\Option\Job;
use Ghostwriter\Compliance\Option\PhpVersion;
use Ghostwriter\Compliance\Service\Composer;
use Ghostwriter\Compliance\ToolInterface;
use Ghostwriter\Config\Contract\ConfigInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Json\Json;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Throwable;
use function getcwd;
use Ghostwriter\Compliance\Service\Composer\ComposerJsonReader;
use Ghostwriter\Compliance\Service\Composer\Extension;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\Option\ComposerCacheFilesDirectoryFinder;

final readonly class MatrixListener implements EventListenerInterface
{
    /**
     * @var string[]
     */
    private const DEPENDENCIES = ['highest', 'locked', 'lowest'];

    public function __construct(
        // remove the damn container im using it to get the tools that were tagged
        private ContainerInterface $container,
        private Composer           $composer,
        private ComposerCacheFilesDirectoryFinder $composerCacheFilesDirectoryFinder = new ComposerCacheFilesDirectoryFinder(),
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        $root = getcwd();

        if ($root === false) {
            throw new RuntimeException('Could not get current working directory');
        }

        chdir($root);

        $composerCacheFilesDirectory = ($this->composerCacheFilesDirectoryFinder)();

        $composerJsonPath = $this->composer->getJsonFilePath($root);
        $composerLockPath = $this->composer->getLockFilePath($root);

        $composerJson = $this->composer->readJsonFile($root);

        $phpVersionConstraint = $composerJson->getPhpVersionConstraint();

        /** @var string $constraints */
        $constraints = $phpVersionConstraint->getVersion();

        $requiredPhpExtensions = array_map(
            fn (Extension $extension) => (string) $extension,
            iterator_to_array($composerJson->getRequiredPhpExtensions())
        );

        $oss = ['macos-latest','ubuntu-latest','windows-latest'];

        foreach ($this->container->tagged(ToolInterface::class) as $tool) {
            if (!$tool->isPresent()) {
                continue;
            }

            $name = $tool->name();
            $command = $tool->command();
            $extensions = $tool->extensions();

            if (! $tool instanceof PHPUnit) {
                $generateMatrixEvent->include(
                    new Job(
                        $name,
                        $command,
                        [...$requiredPhpExtensions, ...$extensions],
                        $composerCacheFilesDirectory,
                        $composerJsonPath,
                        $composerLockPath,
                        'locked',
                        PhpVersion::LATEST
                    )
                );
                continue;
            }

            foreach (PhpVersion::SUPPORTED as $phpVersion) {
                if (!Semver::satisfies(PhpVersion::TO_STRING[$phpVersion], $constraints)) {
                    continue;
                }

                $isExperimental = $phpVersion >= PhpVersion::DEV;

                foreach (ComposerDependency::SUPPORTED as $dependency) {
                    foreach($oss as $os){
                        $generateMatrixEvent->include(
                            new Job(
                                $name,
                                $command,
                                [...$requiredPhpExtensions, ...$extensions],
                                $composerCacheFilesDirectory,
                                $composerJsonPath,
                                $composerLockPath,
                                $dependency,
                                $phpVersion,
                                $isExperimental || $dependency === ComposerDependency::LOWEST,
                                $os
                            )
                        );
                    }
                }
            }
        }

        $gitHubOutput = getenv('GITHUB_OUTPUT') ?: tempnam(
            sys_get_temp_dir(),
            'GITHUB_OUTPUT'
        );

        if (!is_string($gitHubOutput)) {
            dispatchOutputEvent('GITHUB_OUTPUT environment variable not set.');

            $generateMatrixEvent->stopPropagation();

            return;
        }

        $matrix = sprintf('matrix=%s' . PHP_EOL, $generateMatrixEvent->getMatrix());

        file_put_contents(
            $gitHubOutput,
            $matrix,
            FILE_APPEND
        );

        dispatchOutputEvent($matrix);
    }

    /**
     * @throws Throwable
     */
    public function write(string $message): int
    {
        return dispatchOutputEvent($message)->isPropagationStopped() ? Command::FAILURE : Command::SUCCESS;
    }
}
