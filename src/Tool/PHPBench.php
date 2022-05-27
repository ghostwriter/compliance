<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPBench extends AbstractTool
{
    public const PRESENCE_FILES = ['phpbench.json'];

    public function command(): string
    {
        return './vendor/bin/phpbench run --revs=2 --iterations=2 --report=aggregate';
    }
}
