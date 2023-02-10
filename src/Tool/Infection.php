<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Infection extends AbstractTool
{
    public function command(): string
    {
        return 'XDEBUG_MODE=coverage ./vendor/bin/infection';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['infection.json', 'infection.json.dist'];
    }
}
