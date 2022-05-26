<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use const DIRECTORY_SEPARATOR;
use function basename;
use function getenv;
use function trim;

final class Composer extends AbstractTool
{
    public const PRESENCE_FILES = ['composer.json'];

    /**
     * Retrieve the path to composer.json file.
     */
    public function getJsonFilePath(string $root): string
    {
        return $root . DIRECTORY_SEPARATOR . basename(trim(getenv('COMPOSER') ?: 'composer.json'));
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
