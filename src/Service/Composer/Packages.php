<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Generator;
use Ghostwriter\Json\Json;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Stringable;
use function array_map;

/**
 * @implements IteratorAggregate<Package>
 */
final readonly class Packages implements IteratorAggregate, JsonSerializable, Stringable
{
    /**
     * @param array<Package> $packages
     */
    public function __construct(
        private array $packages
    ) {
        foreach ($packages as $package) {
            if (! $package instanceof Package) {
                throw new InvalidArgumentException('Packages must be an array of Package objects');
            }
        }
    }

    public function __toString(): string
    {
        return Json::encode($this);
    }

    /**
     * @return Generator<Package>
     */
    public function getIterator(): Generator
    {
        yield from $this->packages;
    }

    public function jsonSerialize(): array
    {
        return array_map(static fn (Package $package): string => Json::encode($package), $this->packages);
    }
}
