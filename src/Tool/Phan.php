<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Phan extends AbstractTool
{
    public const PRESENCE_FILES = ['.phan/config.php'];

    public function command(): string
    {
        return './vendor/bin/phan';
    }
}
