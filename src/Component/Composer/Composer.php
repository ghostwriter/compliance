<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Component\Composer;

use const DIRECTORY_SEPARATOR;
use function basename;
use function getenv;
use function trim;

final class Composer
{
    private string $composerJsonFileName;

    public function __construct(string $fileName = 'composer.json')
    {
        $this->composerJsonFileName = basename(trim(getenv('COMPOSER') ?: $fileName));
    }

    /**
     * Retrieve the path to composer.json file.
     */
    public function getJsonFilePath(string $root): string
    {
        return $root . DIRECTORY_SEPARATOR . $this->composerJsonFileName;
    }

    /**
     * Retrieve the path to composer.lock file.
     */
    public function getLockFilePath(string $root): string
    {
        $composerJsonPath = $this->getJsonFilePath($root);

        return 'json' === pathinfo($composerJsonPath, PATHINFO_EXTENSION)
            ? substr($composerJsonPath, 0, -4) . 'lock'
            : $composerJsonPath . '.lock';
    }
}
