<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class ComposerRequireChecker extends AbstractTool
{
    public const PRESENCE_FILES = ['composer-require-checker.json'];

    public function command(): string
    {
        return './vendor/bin/composer-require-checker check --config-file=composer-require-checker.json -n -v composer.json';
    }
}
