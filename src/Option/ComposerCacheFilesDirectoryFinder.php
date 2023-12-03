<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;
use function compact;
use Ghostwriter\Compliance\Option\ComposerGlobalHomePathFinder;
use function file_exists;
use function getenv;
use function realpath;
use function implode;
use function trim;
use function sprintf;
use Throwable;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private Process $process,
        private ComposerExecutableFinder $composerExecutableFinder,
        private ComposerGlobalHomePathFinder $composerGlobalHomePathFinder,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(): string
    {
        $isWindowsOS = PHP_OS_FAMILY === 'Windows';

        $composerExecutable = ($this->composerExecutableFinder)($isWindowsOS);

        $composerGlobalHomePath = ($this->composerGlobalHomePathFinder)($composerExecutable);

        $composerGlobalComposerJsonFilePath =
            $composerGlobalHomePath . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerGlobalComposerJsonFilePath)) {
            
            
            $this->process->execute([$composerExecutable, 'global', 'require', 'ghostwriter/psalm-plugin', '--no-interaction']);
            // throw new \RuntimeException(sprintf(
            //     'Could not find composer global composer.json file: %s',
            //     $composerGlobalComposerJsonFilePath
            // ));
        }

        $command = [$composerExecutable, 'config', 'cache-files-dir'];

        [$stdout, $stderr] = $this->process->execute($command);

        if (trim($stderr) !== '') {
            throw new \RuntimeException(sprintf(
                'Could not find composer cache files directory: %s%s%s',
                PHP_EOL,
                $stderr,
                PHP_EOL,
                $stdout
            ));
        }

        return trim($stdout);
    }
}
