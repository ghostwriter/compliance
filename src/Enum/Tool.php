<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

use Ghostwriter\Compliance\Interface\ToolInterface;
use Ghostwriter\Compliance\Tool\Infection;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\Tool\Psalm;

enum Tool: string
{
    case Infection = Infection::class;
    case PHPUnit = PHPUnit::class;
    case Psalm = Psalm::class;

    /** @return class-string<ToolInterface> */
    public function toString(): string
    {
        /** @var class-string<ToolInterface> $this->value */
        return $this->value;
    }
}
