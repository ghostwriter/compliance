<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

interface PhpVersionConstraintInterface
{
    public const LATEST = 'latest';

    public const SUPPORTED = ['7.4', '8.0', '8.1'];

    public function getVersion(): string;
}
