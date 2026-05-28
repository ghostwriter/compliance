<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

final class PHPUnit extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return $this->path('phpunit');
    }

    /** @return list<string> */
    #[Override]
    public function configuration(): array
    {
        return ['phpunit.xml.dist', 'phpunit.xml'];
    }
}
