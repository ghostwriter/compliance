<?php

declare(strict_types=1);

namespace Ghostwriter\ComplianceTests\Unit;

use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
}
