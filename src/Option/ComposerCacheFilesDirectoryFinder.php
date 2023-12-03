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

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private Process $process = new Process(),
        private ComposerExecutableFinder $composerExecutableFinder = new ComposerExecutableFinder(),
        private ComposerGlobalHomePathFinder $composerGlobalHomePathFinder = new ComposerGlobalHomePathFinder(),
    ) {
    }

    public function __invoke(): string
    {
        $composerExecutable = ($this->composerExecutableFinder)($this->process);

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

        return trim($stdout);
    }
}
