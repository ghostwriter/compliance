<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use RuntimeException;
use const PHP_EOL;
use function sprintf;
use function trim;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private Process $process,
        private ComposerExecutableFinder $composerExecutableFinder,
        private Filesystem $filesystem,
    ) {
    }

    public function __invoke(): string
    {
        [$exitCode, $stdout, $stderr] = $this->process->execute([
            ($this->composerExecutableFinder)(),
            'config',
            'cache-files-dir',
            '--no-interaction',
        ]);

        $output = trim($stdout);

        if ($exitCode !== 0 || $output === '') {
            throw new RuntimeException(sprintf(
                'Could not find composer cache files directory: %s%s',
                PHP_EOL,
                $stderr,
            ));
        }

        return $output;
    }
}
