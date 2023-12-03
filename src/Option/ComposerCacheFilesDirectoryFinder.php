<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private Process $process = new Process()
    ) {
    }

    public function find(): string
    {
        [$stdout, $stderr] = $this->process->execute(['composer', 'config', 'cache-files-dir']);

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
