<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Generator;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Stringable;

use function array_map;
use function implode;

/**
 * @implements IteratorAggregate<Extension>
 */
final readonly class Extensions implements IteratorAggregate, JsonSerializable, Stringable
{
    /**
     * @param array<Extension> $extensions
     */
    public function __construct(
        private array $extensions
    ) {
        if ($extensions === []) {
            throw new InvalidArgumentException('Extensions cannot be empty');
        }

        foreach ($extensions as $extension) {
            if (!$extension instanceof Extension) {
                throw new InvalidArgumentException('Extensions must be an array of Extension objects');
            }
        }
    }

    /**
     * @return Generator<Extension>
     */
    public function getIterator(): Generator
    {
        yield from $this->extensions;
    }

    public function jsonSerialize(): array
    {
        return array_map(
            fn (Extension $extension): string => (string) $extension,
            $this->extensions
        );
    }

    public function __toString(): string
    {
        return  implode(', ', $this->jsonSerialize());
    }
}
