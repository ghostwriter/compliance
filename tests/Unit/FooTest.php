<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tests\Unit;

use Ghostwriter\Compliance\Foo;

/**
 * @coversDefaultClass \Ghostwriter\Compliance\Foo
 *
 * @internal
 *
 * @small
 */
final class FooTest extends AbstractTestCase
{
    /** @covers ::test */
    public function test(): void
    {
        self::assertTrue((new Foo())->test());
    }
}
