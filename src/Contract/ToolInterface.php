<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Contract;

interface ToolInterface
{
    public function command(): string;

    /**
     * Configuration files.
     *
     * @return string[]
     */
    public function configuration(): array;

    public function isPresent(): bool;

    public function name(): string;
}
