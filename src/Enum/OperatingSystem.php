<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum OperatingSystem: string
{
    case MACOS = 'macos';
    case UBUNTU = 'ubuntu';
    case WINDOWS = 'windows';

    public function toString(): string
    {
        return $this->value;
    }
}
