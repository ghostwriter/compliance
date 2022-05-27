<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Contract\PresenceInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use function getcwd;
use function getenv;

abstract class AbstractTool implements PresenceInterface
{
    /**
     * Configuration files.
     *
     * @var string[]
     */
    public const PRESENCE_FILES = [];

    public function __construct(
        private Finder $finder,
        private SymfonyStyle $output
    ) {
    }

    public function isPresent(): bool
    {
        $path = getenv('GITHUB_WORKSPACE') ?: getcwd();

        $finder = clone $this->finder;

        $finder->files()
            ->in($path)
            ->depth(0)
            ->sortByName();

//        $this->output->section($path);
//        $this->output->writeln(scandir($path));
//        /** @var SplFileInfo $file */
//        foreach ($finder->getIterator() as $file){
//            $this->output->writeln($file->getPathname());
//        }

        return $finder->name(static::PRESENCE_FILES)->hasResults();
    }
}
