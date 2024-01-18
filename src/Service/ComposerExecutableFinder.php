<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use Ghostwriter\Compliance\Exception\FailedToFindComposerExecutableException;
use function trim;

final readonly class ComposerExecutableFinder
{
    public function __construct(
        private WhereExecutableFinder $whereExecutableFinder,
        private bool $isWindowsOsFamily,
    ) {
    }

    public function __invoke(): string
    {
        [$exitCode, $stdout, $stderr] = Process::execute([
            ($this->whereExecutableFinder)($this->isWindowsOsFamily),
            'composer',
        ]);

        if ($exitCode !== 0 || trim($stderr) !== '') {
            throw new FailedToFindComposerExecutableException($stderr);
        }

        return trim($stdout);
    }
}
