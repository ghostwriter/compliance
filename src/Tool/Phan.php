<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Phan extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/phan';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['.phan/config.php'];
    }
}
