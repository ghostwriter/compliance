<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface\Composer;

interface ComposerFileTypeInterface
{
    /** @var string */
    public const string JSON = 'json';

    /** @var string */
    public const string LOCK = 'lock';
}
