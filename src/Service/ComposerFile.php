<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

interface ComposerFile
{
    /**
     * @var string
     */
    public const JSON = 'composer.json';

    /**
     * @var string
     */
    public const LOCK = 'composer.lock';
}
