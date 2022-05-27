<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Infection extends AbstractTool
{
    public const PRESENCE_FILES = ['infection.json', 'infection.json.dist'];

    public function command(): string
    {
        return 'phpdbg -qrr ./vendor/bin/infection';
    }
}
