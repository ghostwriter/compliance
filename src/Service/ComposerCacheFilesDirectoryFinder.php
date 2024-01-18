<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use Ghostwriter\Compliance\Exception\FailedToFindComposerCacheFilesDirectoryException;
use function trim;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private ComposerExecutableFinder $composerExecutableFinder,
    ) {
    }

    public function __invoke(): string
    {
        [$exitCode, $stdout, $stderr] = Process::execute([
            ($this->composerExecutableFinder)(),
            'config',
            'cache-files-dir',
            '--no-interaction',
        ]);

        $output = trim($stdout);

        if ($exitCode !== 0 || $output === '') {
            throw new FailedToFindComposerCacheFilesDirectoryException($stderr);
        }

        return $output;
    }
}
