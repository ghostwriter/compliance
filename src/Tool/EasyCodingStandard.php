<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class EasyCodingStandard extends AbstractTool
{
    public const PRESENCE_FILES = ['ecs.php'];

    public function command(): string
    {
        return './vendor/bin/ecs check';
    }
}
