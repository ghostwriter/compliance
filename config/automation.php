<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Compliance\Enum\Tool;

return [
    'os' => OperatingSystem::cases(),
    'php' => PhpVersion::cases(),
    'composer' => ComposerStrategy::cases(),
    'tools' => Tool::cases(),
];
