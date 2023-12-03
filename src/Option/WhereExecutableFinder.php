<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final readonly class WhereExecutableFinder
{
    public function __invoke(bool $isWindowsOsFamily = false): string
    {
        return $isWindowsOsFamily ? 'where.exe' : 'which';
    }
}
