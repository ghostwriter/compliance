<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\GPG;

use SensitiveParameter;

use function file_get_contents;

final readonly class PrivateKey
{
    public function __construct(
        #[SensitiveParameter]
        public string $key,
    ) {}

    public static function new(string $key): self
    {
        return new self($key);
    }

    public static function fromFile(string $filePath): self
    {
        return new self(file_get_contents($filePath));
    }

    public static function fromString(string $key): self
    {
        return new self($key);
    }
}
