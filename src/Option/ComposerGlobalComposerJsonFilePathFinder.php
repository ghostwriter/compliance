<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Service\Process;
use Throwable;

final readonly class ComposerGlobalComposerJsonFilePathFinder
{
    public function __construct(
        private Process $process,
        private ComposerExecutableFinder $composerExecutableFinder,
    ) {
    }
    /**
     * @throws Throwable
     */
    public function __invoke(): string
    {
        [$stdout, $stderr] = $this->process->execute([
            ($this->composerExecutableFinder)(),
            '-n',
            'config',
            'home',
        ]);

        if (trim($stderr) !== '') {
            throw new \RuntimeException(sprintf(
                'Could not find global composer json file path: %s%s%s%s',
                PHP_EOL,
                $stderr,
                PHP_EOL,
                $stdout 
            ));
        }

        return trim($stdout) . DIRECTORY_SEPARATOR . 'composer.json';
    }
}
