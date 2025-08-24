<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

interface PhpVersionConstraintInterface
{
    public const array SUPPORTED = ['7.4', '8.0', '8.1', '8.2', '8.3', '8.4', '8.5'];

    public const string LATEST = 'latest';

    public function getVersion(): string;
}
