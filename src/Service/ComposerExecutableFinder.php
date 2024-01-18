<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use RuntimeException;
use const PHP_EOL;
use function sprintf;
use function trim;

final readonly class ComposerExecutableFinder
{
    public function __construct(
        private Process $process,
        private WhereExecutableFinder $whereExecutableFinder,
        private bool $isWindowsOsFamily,
    ) {
    }

    public function __invoke(): string
    {
        [$exitCode, $stdout, $stderr] = $this->process->execute([
            ($this->whereExecutableFinder)($this->isWindowsOsFamily),
            'composer',
        ]);

        if ($exitCode !== 0 || trim($stderr) !== '') {
            throw new RuntimeException(sprintf(
                'Could not find composer executable: %s%s%s%s',
                PHP_EOL,
                $stderr,
                PHP_EOL,
                $stdout
            ));
        }

        return trim($stdout);
    }
}
