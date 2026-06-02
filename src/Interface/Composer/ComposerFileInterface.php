<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface\Composer;

interface ComposerFileInterface
{
    /** @var string */
    public const string JSON = 'composer.json';

    /** @var string */
    public const string LOCK = 'composer.lock';
}
