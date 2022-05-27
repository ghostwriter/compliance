<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPCSFixer extends AbstractTool
{
    public const PRESENCE_FILES = ['.php-cs-fixer.php', '.php-cs-fixer.dist.php'];

    public function command(): string
    {
        return './vendor/bin/php-cs-fixer';
    }
}
