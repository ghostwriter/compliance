<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

final readonly class DependencyName implements \JsonSerializable, \Stringable
{
    public function __construct(
        private string $content
    ) {
        if (trim($content) === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
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

    public function isPhpExtension(): bool
    {
        return str_starts_with($this->content, 'ext-');
    }
}


