<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Generator;
use Ghostwriter\Json\Interface\JsonInterface;
use IteratorAggregate;
use Override;

/**
 * @implements IteratorAggregate<Extension|Package>
 */
final readonly class RequireList implements IteratorAggregate
{
    /**
     * @param list<Extension|Package> $requireList
     */
    public function __construct(
        private array $requireList,
    ) {}

    /**
     * @param array<string,string> $require
     */
    public static function new(array $require, JsonInterface $json): self
    {
        $requireList = [];

        foreach ($require as $name => $version) {
            $dependencyName = DependencyName::new($name);

            $dependencyVersion = DependencyVersion::new($version);

            if ($dependencyName->isPhpExtension()) {
                $requireList[$name] = Extension::new($dependencyName, $dependencyVersion);

                continue;
            }

            $requireList[$name] = Package::new($dependencyName, $dependencyVersion, $json);
        }

        return new self($requireList);
    }

    #[Override]
    public function getIterator(): Generator
    {
        yield from $this->requireList;
    }
}
