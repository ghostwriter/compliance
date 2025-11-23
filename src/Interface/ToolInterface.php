<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface;

interface ToolInterface
{
    public function command(): string;

    /** @return list<string> */
    public function configuration(): array;

    /** @return list<string> */
    public function extensions(): array;

    public function isPresent(): bool;

    public function name(): string;
}
