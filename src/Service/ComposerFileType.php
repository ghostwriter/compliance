<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

interface ComposerFileType
{
    /**
     * @var string
     */
    public const JSON = 'json';

    /**
     * @var string
     */
    public const LOCK = 'lock';
}