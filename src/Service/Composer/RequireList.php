<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Generator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<Package|Extension>
 */
final readonly class RequireList implements IteratorAggregate
{
    /**
     * @param array<Extension|Package> $requireList
     */
    public function __construct(
        private array $requireList,
    ) {
    }

    public function getIterator(): Generator
    {
        yield from $this->requireList;
    }

    public static function new(array $require): self
    {
        $requireList = [];

        foreach ($require as $name => $version) {
            $dependencyName = DependencyName::new($name);
            $dependencyVersion = DependencyVersion::new($version);

            $requireList[$name] = $dependencyName->isPhpExtension()
                ? Extension::new($dependencyName, $dependencyVersion)
                : Package::new($dependencyName, $dependencyVersion);
        }

        return new self($requireList);
    }
}
