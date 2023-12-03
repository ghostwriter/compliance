<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;
use Throwable;

final readonly class ComposerGlobalHomePathFinder
{
    public function __construct(
        private Process $process,
    ) {
    }
    /**
     * @throws Throwable
     */
    public function __invoke(
        string $composerExecutable,
    ): string
    {
        [$stdout, $stderr] = $this->process->execute([
            $composerExecutable,
            '-n',
            'config',
            '--global',
            'home',
        ]);

        if (trim($stderr) !== '') {
            throw new \RuntimeException(sprintf(
                'Could not find composer global home path: %s%s%s',
                PHP_EOL,
                $stderr,
                PHP_EOL,
                $stdout 
            ));
        }

        return trim($stdout);
    }
}
