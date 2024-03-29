<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

interface ToolInterface
{
    public function command(): string;

    /**
     * @return array<string>
     */
    public function configuration(): array;

    /**
     * @return array<string>
     */
    public function extensions(): array;

    public function isPresent(): bool;

    public function name(): string;

    // public function package(): string;
}
