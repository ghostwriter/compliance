<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class ECS extends AbstractTool
{
    public function command(): string
    {
        return './vendor/bin/ecs check';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['ecs.php'];
    }
}
