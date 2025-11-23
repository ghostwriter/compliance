<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

interface ComposerFile
{
    /** @var string */
    public const string JSON = 'composer.json';

    /** @var string */
    public const string LOCK = 'composer.lock';
}
