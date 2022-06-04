<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class ComposerRequireChecker extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/composer-require-checker check --config-file=composer-require-checker.json -n -v composer.json';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['composer-require-checker.json'];
    }
}
