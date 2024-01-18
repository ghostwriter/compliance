<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

final readonly class RequirePhp implements PhpVersionConstraint
{
    public function __construct(
        private string $version,
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public static function new(string $phpVersion): self
    {
        return new self($phpVersion);
    }
}
