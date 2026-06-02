<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use Ghostwriter\Compliance\Composer\ComposerManager;
use Ghostwriter\Compliance\Composer\Json\ComposerJsonReader;
use Ghostwriter\Compliance\Composer\Lock\ComposerLockReader;
use Ghostwriter\Compliance\Container\ComplianceProvider;
use Ghostwriter\Compliance\EnvironmentVariables;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\Unit\AbstractTestCase;
use Throwable;

use const DIRECTORY_SEPARATOR;

#[CoversClass(ComposerManager::class)]
#[UsesClass(ComposerJsonReader::class)]
#[UsesClass(ComposerLockReader::class)]
#[UsesClass(EnvironmentVariables::class)]
#[UsesClass(ComplianceProvider::class)]
final class ComposerTest extends AbstractTestCase
{
    /** @throws Throwable */
    public function testGetJsonFilePath(): void
    {
        $currentWorkingDirectory = $this->filesystem->currentWorkingDirectory();

        self::assertSame(
            $this->composer->getJsonFilePath($currentWorkingDirectory),
            $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'composer.json',
        );
    }

    /** @throws Throwable */
    public function testGetLockFilePath(): void
    {
        $currentWorkingDirectory = $this->filesystem->currentWorkingDirectory();

        self::assertSame(
            $this->composer->getLockFilePath($currentWorkingDirectory),
            $currentWorkingDirectory . DIRECTORY_SEPARATOR . 'composer.lock',
        );
    }
}
