<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPUnit extends AbstractTool
{
    public const PRESENCE_FILES = ['phpunit.xml.dist', 'phpunit.xml'];

    public function command(): string
    {
        return './vendor/bin/phpunit';
    }
}
