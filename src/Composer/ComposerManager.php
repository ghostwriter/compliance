<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer;

use Ghostwriter\Compliance\Composer\Json\ComposerJson;
use Ghostwriter\Compliance\Composer\Json\ComposerJsonReader;
use Ghostwriter\Compliance\Composer\Lock\ComposerLock;
use Ghostwriter\Compliance\Composer\Lock\ComposerLockReader;
use Ghostwriter\Compliance\Interface\Composer\ComposerFileInterface;
use Ghostwriter\Compliance\Interface\Composer\ComposerFileTypeInterface;
use Ghostwriter\Compliance\Interface\Composer\PhpVersionConstraintInterface;
use Throwable;

use const DIRECTORY_SEPARATOR;
use const PATHINFO_EXTENSION;

use function basename;
use function getenv;
use function implode;
use function mb_substr;
use function mb_trim;
use function pathinfo;

final readonly class ComposerManager
{
    public function __construct(
        private ComposerJsonReader $composerJsonReader,
        private ComposerLockReader $composerLockReader,
    ) {}

    /** Retrieve the path to composer.json file. */
    public function getJsonFilePath(string $root): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [$root, basename(mb_trim(getenv('COMPOSER') ?: ComposerFileInterface::JSON))]
        );
    }

    /** Retrieve the path to composer.lock file. */
    public function getLockFilePath(string $root): string
    {
        $composerJsonPath = $this->getJsonFilePath($root);

        return pathinfo($composerJsonPath, PATHINFO_EXTENSION) === ComposerFileTypeInterface::JSON
            ? mb_substr($composerJsonPath, 0, -4) . 'lock'
            : $composerJsonPath . '.lock';
    }

    /** @throws Throwable */
    public function getPhpVersionConstraint(string $path): PhpVersionConstraintInterface
    {
        return $this->readJsonFile($path)
            ->getPhpVersionConstraint();
    }

    /** @throws Throwable */
    public function getRequire(string $path): RequireList
    {
        return $this->readJsonFile($path)
            ->getRequire();
    }

    /** @throws Throwable */
    public function getRequireDev(string $path): RequireDevList
    {
        return $this->readJsonFile($path)->getRequireDev();
    }

    /** @throws Throwable */
    public function readJsonFile(string $path): ComposerJson
    {
        $composerJsonPath = $this->getJsonFilePath($path);

        return $this->composerJsonReader->read($composerJsonPath);
    }

    /** @throws Throwable */
    public function readLockFile(string $path): ComposerLock
    {
        $composerLockPath = $this->getLockFilePath($path);

        return $this->composerLockReader->read($composerLockPath);
    }
}
