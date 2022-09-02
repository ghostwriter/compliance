<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tests\Unit\Service;

use Ghostwriter\Compliance\Service\Composer;
use Ghostwriter\Compliance\Tests\Unit\AbstractTestCase;
use function getcwd;

/**
 * @internal
 *
 * @small
 *
 * @coversDefaultClass \Ghostwriter\Compliance\Service\Composer
 */
final class ComposerTest extends AbstractTestCase
{
    /** @covers \Ghostwriter\Compliance\Service\Composer::getJsonFilePath */
    public function testGetJsonFilePath(): void
    {
        $root = getcwd();
        self::assertSame((new Composer())->getJsonFilePath($root), $root . '/composer.json');
    }

    /**
     * @covers \Ghostwriter\Compliance\Service\Composer::getJsonFilePath
     * @covers \Ghostwriter\Compliance\Service\Composer::getLockFilePath
     */
    public function testGetLockFilePath(): void
    {
        $root = getcwd();
        self::assertSame((new Composer())->getLockFilePath($root), $root . '/composer.lock');
    }
}
