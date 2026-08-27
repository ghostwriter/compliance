<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Override;

final class StructArmed extends AbstractTool
{
    #[Override]
    public function command(): string
    {
        return $this->path('structarmed') . ' analyse';
    }

    /** @return list<string> */
    #[Override]
    public function configuration(): array
    {
        return ['structarmed.php'];
    }
}
