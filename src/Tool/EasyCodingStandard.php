<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class EasyCodingStandard extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/ecs check';
    }

    public function configuration(): array
    {
        return ['ecs.php'];
    }
}
