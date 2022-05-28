<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Phan extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/phan';
    }

    public function configuration(): array
    {
        return ['.phan/config.php'];
    }
}
