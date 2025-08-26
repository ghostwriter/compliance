<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

interface PhpVersionConstraintInterface
{
    public function getVersion(): string;
}
