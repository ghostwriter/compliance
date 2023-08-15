<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Rector extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/rector process --dry-run || true';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['rector.php', 'rector.php.dist'];
    }
}
