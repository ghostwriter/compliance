<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Generator;
use Ghostwriter\Json\Interface\JsonInterface;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Override;
use Stringable;
use Throwable;

use function array_map;

/**
 * @implements IteratorAggregate<Package>
 */
final readonly class Packages implements IteratorAggregate, JsonSerializable, Stringable
{
    /**
     * @param list<Package> $packages
     *
     * @throws Throwable
     */
    public function __construct(
        private array $packages,
        private JsonInterface $json
    ) {
        foreach ($packages as $package) {
            if (! $package instanceof Package) {
                throw new InvalidArgumentException('Packages must be an array of Package objects');
            }
        }
    }

    /** @throws Throwable */
    #[Override]
    public function __toString(): string
    {
        return $this->json->encode($this);
    }

    /** @return Generator<Package> */
    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->packages;
    }

    /** @throws Throwable */
    #[Override]
    public function jsonSerialize(): array
    {
        return array_map(static fn (Package $package): string => $this->json->encode($package), $this->packages);
    }
}
