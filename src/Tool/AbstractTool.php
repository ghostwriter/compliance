<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Interface\ToolInterface;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;

use const DIRECTORY_SEPARATOR;

use function implode;
use function in_array;
use function mb_strtolower;
use function preg_replace;
use function realpath;
use function str_replace;

abstract class AbstractTool implements ToolInterface
{
    public function __construct(
        private readonly FilesystemInterface $filesystem,
        private readonly EnvironmentVariables $environmentVariables
    ) {}

    #[Override]
    public function command(): string
    {
        return 'composer ' . str_replace(
            'p-h-p-',
            'php',
            mb_strtolower((string) preg_replace('#([a-zA-Z])(?=[A-Z])#', '$1-', $this->name()))
        );
    }

    #[Override]
    public function extensions(): array
    {
        return ['mbstring'];
    }

    #[Override]
    public function isPresent(): bool
    {
        $configuration = $this->configuration();

        foreach ($this->filesystem->listDirectory($this->environmentVariables->get('GITHUB_WORKSPACE')) as $file) {
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

    #[Override]
    public function name(): string
    {
        return str_replace(__NAMESPACE__ . '\\', '', static::class);
    }

    public function path(string $tool): string
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            $this->environmentVariables->get('GITHUB_WORKSPACE', $this->filesystem->currentWorkingDirectory()),
            'vendor',
            'bin',
            $tool,
        ]);

        return sprintf('(%s || %s)', $path, $tool);

        if (false === realpath($path)) {
            return $tool;
        }

        return $path;
    }

    /** @return list<string> */
    #[Override]
    abstract public function configuration(): array;
}
