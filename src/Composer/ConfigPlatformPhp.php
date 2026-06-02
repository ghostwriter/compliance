<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer;

use Ghostwriter\Compliance\Interface\Composer\PhpVersionConstraintInterface;
use Override;

final readonly class ConfigPlatformPhp implements PhpVersionConstraintInterface
{
    public function __construct(
        private string $version,
    ) {}

    #[Override]
    public function getVersion(): string
    {
        return $this->version;
    }
}
