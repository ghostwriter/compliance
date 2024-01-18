<?php

declare(strict_types=1);

namespace Ghostwriter\ComplianceTests\Unit\Service;

use Ghostwriter\Compliance\Service\Composer;
use Ghostwriter\ComplianceTests\Unit\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use function getcwd;

#[CoversClass(Composer::class)]
final class ComposerTest extends AbstractTestCase
{
    public function testGetJsonFilePath(): void
    {
        $root = getcwd();
        self::assertSame((new Composer())->getJsonFilePath($root), $root . '/composer.json');
    }

    public function testGetLockFilePath(): void
    {
        $root = getcwd();
        self::assertSame((new Composer())->getLockFilePath($root), $root . '/composer.lock');
    }
}
