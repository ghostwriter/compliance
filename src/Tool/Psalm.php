<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

use Ghostwriter\Compliance\Contract\PresenceInterface;

final class Psalm extends AbstractTool implements PresenceInterface
{
    public const PRESENCE_FILES = ['psalm.xml.dist', 'psalm.xml'];
}
