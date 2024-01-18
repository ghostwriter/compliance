<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Generator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<Package|Extension>
 */
final readonly class RequireDevList implements IteratorAggregate
{
    /**
     * @param array<Extension|Package> $requireDevList
     */
    public function __construct(
        private array $requireDevList,
    ) {
    }

    public function getIterator(): Generator
    {
        yield from $this->requireDevList;
    }

    public static function new(array $requireDev): self
    {
        $requireDevList = [];

        foreach ($requireDev as $name => $version) {
            $dependencyName = DependencyName::new($name);
            $dependencyVersion = DependencyVersion::new($version);

            $requireDevList[$name] = $dependencyName->isPhpExtension()
                ? Extension::new($dependencyName, $dependencyVersion)
                : Package::new($dependencyName, $dependencyVersion);
        }

        return new self($requireDevList);
    }
}
