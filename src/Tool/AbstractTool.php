<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Contract\PresenceInterface;
use Phar;
use SplFileInfo;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use function dirname;
use function getcwd;
use function getenv;
use function iterator_to_array;
use function var_dump;
use const PHP_EOL;

abstract class AbstractTool implements PresenceInterface
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
        $phar = extension_loaded('Phar') ? Phar::running(false) : '';
        $path = str_replace('phar://' . $phar, '', getcwd());

        $finder = clone $this->finder;
        $finder
            ->files()
            ->in($path)
            ->depth(0)
            ->sortByName();

        $this->output->error($path);

        /** @var SplFileInfo $file */
        foreach ($finder->getIterator() as $file){
            $this->output->comment($file->getPathname());
        }

        return $finder
            ->name(static::PRESENCE_FILES)->hasResults();
    }
}
