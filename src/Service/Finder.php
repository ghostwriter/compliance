<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final readonly class Finder
{
    /**
     * @param string $directory
     * @return Generator<SplFileInfo>
     */
    public function findIn(string $directory): Generator
    {
        yield from new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory)
        );
    }
}
