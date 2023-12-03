<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;
use Throwable;

final readonly class ComposerExecutableFinder
{
    public function __construct(
        private Process $process,
        private WhereExecutableFinder $whereExecutableFinder,
    ) {
    }
    /**
     * @throws Throwable
     */
    public function __invoke(bool $isWindowsOsFamily = false): string
    {
        $where = ($this->whereExecutableFinder)($isWindowsOsFamily);

        [$stdout, $stderr] = $this->process->execute([$where, 'composer']);

        if (trim($stderr) !== '') {
            throw new \RuntimeException(sprintf(
                'Could not find composer executable: %s%s%s',
                PHP_EOL,
                $stderr,
                PHP_EOL,
                $stdout 
            ));
        }

        return trim($stdout);
    }
}
