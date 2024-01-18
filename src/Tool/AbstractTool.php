<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\Service\Filesystem;
use Ghostwriter\Compliance\ToolInterface;
use function getcwd;
use function in_array;
use function mb_strtolower;
use function preg_replace;
use function str_replace;

abstract class AbstractTool implements ToolInterface
{
    public function __construct(
        private Filesystem $filesystem,
        private EnvironmentVariables $environmentVariables
    ) {
    }

    public function command(): string
    {
        return 'composer ' . str_replace(
            'p-h-p-',
            'php',
            mb_strtolower(preg_replace('#([a-zA-Z])(?=[A-Z])#', '$1-', $this->name()))
        );
    }

    public function extensions(): array
    {
        return ['pcov'];
    }

    public function isPresent(): bool
    {
        $configuration = $this->configuration();

        foreach ($this->filesystem->findIn(
            $this->environmentVariables->get('GITHUB_WORKSPACE')
        ) as $file) {
            if (! $file->isFile()) {
                continue;
            }

            if (! in_array($file->getFilename(), $configuration, true)) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function name(): string
    {
        return str_replace(__NAMESPACE__ . '\\', '', static::class);
    }

    abstract public function configuration(): array;
}
