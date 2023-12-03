<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Generator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<Dependency|Package|Extension>
 */
final readonly class RequireDevList implements IteratorAggregate
{
    /**
     * @param array<Dependency|Package|Extension> $list
     */
    public function __construct(
        private array $list,
    ) {
    }

    public function getIterator(): Generator
    {
        yield from $this->list;
    }
}
