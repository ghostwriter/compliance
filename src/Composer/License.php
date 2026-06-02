<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer;

use InvalidArgumentException;
use JsonSerializable;
use Override;
use Stringable;

use function mb_trim;

final readonly class License implements JsonSerializable, Stringable
{
    public function __construct(
        private string $content
    ) {
        if (mb_trim($content) === '') {
            throw new InvalidArgumentException('License cannot be empty');
        }
    }

    public static function new(?string $content): self
    {
        return match (true) {
            null === $content => throw new InvalidArgumentException('License cannot be null'),
            default => new self($content)
        };
    }

    #[Override]
    public function __toString(): string
    {
        return $this->content;
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [$this->content];
    }
}
