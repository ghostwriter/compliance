<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPCSFixer extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/php-cs-fixer';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['.php-cs-fixer.php', '.php-cs-fixer.dist.php'];
    }
}
