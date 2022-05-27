<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Contract\ToolInterface;
use Symfony\Component\Finder\Finder;
use function getcwd;
use function getenv;

abstract class AbstractTool implements ToolInterface
{
    /**
     * Configuration files.
     *
     * @var string[]
     */
    public const PRESENCE_FILES = [];

    protected string $phpVersion;

    public function __construct(private Finder $finder)
    {
    }

    public function isPresent(): bool
    {
        $path = getenv('GITHUB_WORKSPACE') ?: getcwd();

        $finder = clone $this->finder;

        $finder->files()
            ->in($path)
            ->depth(0)
            ->sortByName();

        return $finder->name(static::PRESENCE_FILES)->hasResults();
    }

    public function name(): string
    {
        return str_replace(__NAMESPACE__ . '\\', '', static::class);
    }

    public function phpVersion(): string
    {
        return $this->phpVersion;
    }
}
