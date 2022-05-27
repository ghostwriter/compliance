<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Codeception extends AbstractTool
{
    public const PRESENCE_FILES = ['codeception.yml.dist', 'codeception.yml'];

    public function command(): string
    {
        return './vendor/bin/codecept run';
    }
}
