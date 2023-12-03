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
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(): string
    {
        [$stdout, $stderr] = $this->process->execute([
            ($this->composerExecutableFinder)(), 
            'config', 
            'cache-files-dir',
            '--no-interaction',
        ]);

        $output = trim($stdout);

        if ($output === '') {
            throw new \RuntimeException(sprintf(
                'Could not find composer cache files directory: %s%s',
                PHP_EOL,
                $stderr,
            ));
        }

        return $output;
    }
}
