<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum OperatingSystem: string
{
    case macos = 'macos-latest';
    case ubuntu = 'ubuntu-latest';
    case windows = 'windows-latest';
}
