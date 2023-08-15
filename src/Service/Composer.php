<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use const DIRECTORY_SEPARATOR;
use function basename;
use function getenv;
use function pathinfo;
use function trim;

final class Composer
{
    /**
     * @var string
     */
    public const FILE_JSON = 'composer.json';

    /**
     * @var string
     */
    public const FILE_LOCK = 'composer.lock';

    /**
     * @var string
     */
    public const PHP = 'php';

    /**
     * @var string
     */
    public const REQUIRE = 'require';

    /**
     * Retrieve the path to composer.json file.
     */
    public function getJsonFilePath(string $root): string
    {
        return $root . DIRECTORY_SEPARATOR . basename(trim(getenv('COMPOSER') ?: self::FILE_JSON));
    }

    /**
     * Retrieve the path to composer.lock file.
     */
    public function getLockFilePath(string $root): string
    {
        $composerJsonPath = $this->getJsonFilePath($root);

        return pathinfo($composerJsonPath, PATHINFO_EXTENSION) === 'json'
            ? mb_substr($composerJsonPath, 0, -4) . 'lock'
            : $composerJsonPath . '.lock';
    }
}
