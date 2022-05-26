<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use function dirname;
use function getcwd;
use function getenv;
use function iterator_to_array;
use function var_dump;
use const PHP_EOL;

abstract class AbstractTool
{
    /**
     * Configuration files.
     *
     * @var array<array-key,string>
     */
    public const PRESENCE_FILES = [];

    public function __construct(private Finder $finder, private SymfonyStyle $output)
    {
    }

    public function isPresent(): bool
    {
        $path = getenv('GITHUB_WORKSPACE') ?: getcwd() ?: dirname(__DIR__, 2);

        $finder = clone $this->finder;
        $finder
            ->in($path)
            ->sortByName();

        $this->output->section($path);
        $this->output->write(iterator_to_array($finder->getIterator()));



        return $finder
            ->name(static::PRESENCE_FILES)->hasResults();
    }
}
