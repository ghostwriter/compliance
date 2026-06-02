<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface\Composer;

interface PhpVersionConstraintInterface
{
    public function getVersion(): string;
}
