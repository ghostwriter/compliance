<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

use const DIRECTORY_SEPARATOR;

use function implode;

final class PHPUnit extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return implode(DIRECTORY_SEPARATOR, ['vendor', 'bin', 'phpunit']);
    }

    /** @return list<string> */
    #[Override]
    public function configuration(): array
    {
        return ['phpunit.xml.dist', 'phpunit.xml'];
    }
}
