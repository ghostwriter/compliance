<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;
use function compact;
use Ghostwriter\Compliance\Option\ComposerGlobalHomePathFinder;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private Process $process = new Process(),
        private ComposerExecutableFinder $composerExecutableFinder = new ComposerExecutableFinder(),
        private ComposerGlobalHomePathFinder $composerGlobalHomePathFinder = new ComposerGlobalHomePathFinder(),
    ) {
    }

    public function find(): string
    {
        $isWindowsOS = PHP_OS_FAMILY === 'Windows';

        $composerExecutable = ($this->composerExecutableFinder)($this->process, $isWindowsOS);

        $composerGlobalHomePath = ($this->composerGlobalHomePathFinder)($this->process, $composerExecutable);

        $composerGlobalComposerJsonFilePath = sprintf(
            '%s%scomposer.json',
            $composerGlobalHomePath,
            DIRECTORY_SEPARATOR
        );

        if ( ! file_exists($composerGlobalComposerJsonFilePath)) {
            throw new \RuntimeException(sprintf(
                'Could not find composer global composer.json file: %s',
                $composerGlobalComposerJsonFilePath
            ));
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

        $composerCacheFilesDirectory = trim($stdout);

        if ('' === $composerCacheFilesDirectory) {
            $composerCacheFilesDirectory = implode(
                DIRECTORY_SEPARATOR,
                [getenv('HOME') ?: realpath('~/'), '.composer', 'cache', 'files']
            );
        }

        return $composerCacheFilesDirectory;
    }
}
