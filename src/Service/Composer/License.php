<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;
use function trim;

final readonly class License implements JsonSerializable, Stringable
{
    public function __construct(
        private string $content
    ) {
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function jsonSerialize(): array
    {
        return [$this->content];
    }

    public static function new(?string $content): self
    {
        return match (true) {
            $content === null => throw new InvalidArgumentException('License cannot be null'),
            trim($content) === '' => throw new InvalidArgumentException('License cannot be empty'),
            default => new self($content)
        };
    }
}
