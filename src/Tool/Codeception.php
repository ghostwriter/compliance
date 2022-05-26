<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class Codeception extends AbstractTool
{
    public const PRESENCE_FILES = ['codeception.yml.dist', 'codeception.yml'];
}
