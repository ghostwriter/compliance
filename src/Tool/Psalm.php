<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Psalm extends AbstractTool
{
    public const PRESENCE_FILES = ['psalm.xml.dist', 'psalm.xml'];
}
