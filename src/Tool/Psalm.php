<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

final class Psalm extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return 'composer psalm';
    }

    /**
     * @return list<string>
     */
    #[Override]
    public function configuration(): array
    {
        return ['psalm.xml.dist', 'psalm.xml'];
    }
}
