<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;

final readonly class ComposerExecutableFinder
{
    public function __invoke(
        Process $process,
        bool $isWindowsOS = false
    ): string
    {
        $where = $isWindowsOS ? 'where.exe' : 'which';

        [$stdout, $stderr] = $process->execute([$where, 'composer']);

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
