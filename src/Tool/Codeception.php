<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Codeception extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/codecept run';
    }

    public function configuration(): array
    {
        return ['codeception.yml.dist', 'codeception.yml'];
    }
}
