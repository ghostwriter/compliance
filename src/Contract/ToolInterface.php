<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Contract;

interface ToolInterface
{
    public function command(): string;

    public function isPresent(): bool;

    public function name(): string;

    public function phpVersion(): string;
}
