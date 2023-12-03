<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use const DIRECTORY_SEPARATOR;
use function basename;
use function getenv;
use function pathinfo;
use function trim;
use Ghostwriter\Compliance\Service\Composer\ComposerJsonReader;
use Ghostwriter\Compliance\Service\Composer\ComposerLockReader;
use Ghostwriter\Compliance\Service\Composer\ComposerLock;
use Ghostwriter\Compliance\Service\Composer\ComposerJson;
use Ghostwriter\Compliance\Service\Composer\RequireDevList;
use Ghostwriter\Compliance\Service\Composer\RequireList;

final readonly class Composer
{
    public function __construct(
        private ComposerJsonReader $composerJsonReader = new ComposerJsonReader(),
        private ComposerLockReader $composerLockReader = new ComposerLockReader(),
    ) {
    }

    /**
     * Retrieve the path to composer.json file.
     */
    public function getJsonFilePath(string $root): string
    {
        return $root . DIRECTORY_SEPARATOR . basename(
            trim(getenv('COMPOSER') ?: ComposerFile::JSON)
        );
    }

    /**
     * Retrieve the path to composer.lock file.
     */
    public function getLockFilePath(string $root): string
    {
        $composerJsonPath = $this->getJsonFilePath($root);

        return pathinfo($composerJsonPath, PATHINFO_EXTENSION) === ComposerFileType::JSON
            ? mb_substr($composerJsonPath, 0, -4) . 'lock'
            : $composerJsonPath . '.lock';
    }

    public function readJsonFile(string $path): ComposerJson
    {
        $composerJsonPath = $this->getJsonFilePath($path);

        return $this->composerJsonReader->read($composerJsonPath);
    }

    public function readLockFile(string $path): ComposerLock
    {
        $composerLockPath = $this->getLockFilePath($path);

        return $this->composerLockReader->read($composerLockPath);
    }

    public function getPhpVersionConstraint(string $path): PhpVersionConstraint
    {
        return $this->readJsonFile($path)->getPhpVersionConstraint();
    }

    public function getRequire(string $path): RequireList
    {
        return $this->readJsonFile($path)->getRequire();
    }

    public function getRequireDev(string $path): RequireDevList
    {
        return $this->readJsonFile($path)->getRequireDev();
    }
}
