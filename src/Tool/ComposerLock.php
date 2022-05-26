<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class ComposerLock extends AbstractTool
{
    public const PRESENCE_FILES = ['composer.lock'];
}
