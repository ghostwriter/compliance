<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer;

use Generator;
use Ghostwriter\Json\Interface\JsonInterface;
use IteratorAggregate;
use Override;

/**
 * @implements IteratorAggregate<Extension|Package>
 */
final readonly class RequireDevList implements IteratorAggregate
{
    /** @param list<Extension|Package> $requireDevList */
    public function __construct(
        private array $requireDevList,
    ) {}

    public static function new(array $requireDev, JsonInterface $json): self
    {
        $requireDevList = [];

        foreach ($requireDev as $name => $version) {
            $dependencyName = DependencyName::new($name);
            $dependencyVersion = DependencyVersion::new($version);

            $requireDevList[$name] = $dependencyName->isPhpExtension()
                ? Extension::new($dependencyName, $dependencyVersion)
                : Package::new($dependencyName, $dependencyVersion, $json);
        }

        return new self($requireDevList);
    }

    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->requireDevList;
    }
}
