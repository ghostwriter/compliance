<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class GrumPHP extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/grumphp run';
    }

    public function configuration(): array
    {
        return ['grumphp.xml.dist', 'grumphp.xml'];
    }
}
