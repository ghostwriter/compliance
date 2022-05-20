<?php

declare(strict_types=1);

namespace Ghostwriter\AutomatedCompliance\Tests\Unit;

use Ghostwriter\AutomatedCompliance\Foo;

/**
 * @coversDefaultClass \Ghostwriter\AutomatedCompliance\Foo
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
