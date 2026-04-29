<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

use const DIRECTORY_SEPARATOR;

use function implode;

final class Infection extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return 'infection';
        // implode(DIRECTORY_SEPARATOR, ['vendor', 'bin', 'infection']);
    }

    /** @return list<string> */
    #[Override]
    public function configuration(): array
    {
        return [];
        // return ['infection.json5', 'infection.json', 'infection.json.dist', 'infection.json5.dist'];
    }
}
