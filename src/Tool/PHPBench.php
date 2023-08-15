<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPBench extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/phpbench run --revs=2 --iterations=2 --report=aggregate';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['phpbench.json', 'phpbench.json.dist'];
    }
}
