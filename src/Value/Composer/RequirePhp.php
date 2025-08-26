<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use InvalidArgumentException;
use Override;

use function mb_trim;

final readonly class RequirePhp implements PhpVersionConstraintInterface
{
    public function __construct(
        private string $version,
    ) {
        if (mb_trim($version) === '') {
            throw new InvalidArgumentException('PHP version cannot be empty');
        }
    }

    public static function new(string $phpVersion): self
    {
        return new self($phpVersion);
    }

    #[Override]
    public function getVersion(): string
    {
        return $this->version;
    }
}
