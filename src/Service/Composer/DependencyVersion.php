<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use InvalidArgumentException;
use JsonSerializable;
use Stringable;
use function trim;

final readonly class DependencyVersion implements JsonSerializable, Stringable
{
    public function __construct(
        private string $content
    ) {
        if (trim($content) === '') {
            throw new InvalidArgumentException('Version cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function jsonSerialize(): array
    {
        return [$this->content];
    }

    public static function new(string $content): self
    {
        return new self($content);
    }
}
