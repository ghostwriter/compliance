<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

use const DIRECTORY_SEPARATOR;

use function implode;

final class Psalm extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return 'psalm';
        //return implode(DIRECTORY_SEPARATOR, ['vendor', 'bin', 'psalm']);
    }

    /** @return list<string> */
    #[Override]
    public function configuration(): array
    {
        return ['psalm.xml.dist', 'psalm.xml'];
    }
}
