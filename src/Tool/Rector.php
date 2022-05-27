<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Rector extends AbstractTool
{
    public const PRESENCE_FILES = ['rector.php'];

    public function command(): string
    {
        return './vendor/bin/rector process';
    }
}
