<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Contract\ToolInterface;
use Symfony\Component\Finder\Finder;
use function getcwd;
use function getenv;

abstract class AbstractTool implements ToolInterface
{
    public function __construct(
        private Finder $finder
    ) {
        $this->finder = clone $finder;
    }

    abstract public function configuration(): array;

    public function extensions(): array
    {
        return ['xdebug'];
    }

    public function isPresent(): bool
    {
        $path = getenv('GITHUB_WORKSPACE') ?: getcwd();

        return $this->finder->files()
            ->in($path)
            ->depth(0)
            ->sortByName()
            ->name($this->configuration())
            ->hasResults();
    }

    public function name(): string
    {
        return str_replace(__NAMESPACE__ . '\\', '', static::class);
    }
}
