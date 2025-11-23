<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Shell;

use Throwable;

final readonly class ComposerExecutableFinder
{
    public function __construct(
        private WhereExecutableFinder $whereExecutableFinder,
    ) {}

    /** @throws Throwable */
    public function __invoke(): string
    {
        return ($this->whereExecutableFinder)('composer');
    }
}
