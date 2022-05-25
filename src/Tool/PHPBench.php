<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Contract\PresenceInterface;

final class PHPBench extends AbstractTool implements PresenceInterface
{
    public const PRESENCE_FILES = ['phpbench.json'];
}
