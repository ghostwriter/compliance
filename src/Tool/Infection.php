<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

final class Infection extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return $this->path('infection');
    }

    /** @return list<string> */
    #[Override]
    public function configuration(): array
    {
        return [];
        // return ['infection.json5', 'infection.json', 'infection.json.dist', 'infection.json5.dist'];
    }
}
