<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

final readonly class ComposerLock
{
    /**
     * @param array<int,mixed> $contents
     */
    public function __construct(
        private string $path,
        private array $contents,
    ) {
    }

    public function getComposerJsonPath(): string
    {
        return $this->path;
    }

    /**
     * @return array<int,mixed>
     */
    public function getContents(): array
    {
        return $this->contents;
    }
}
