<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class GrumPHP extends AbstractTool
{
    public const PRESENCE_FILES = ['grumphp.xml.dist', 'grumphp.xml'];

    public function command(): string
    {
        return './vendor/bin/grumphp run';
    }
}
