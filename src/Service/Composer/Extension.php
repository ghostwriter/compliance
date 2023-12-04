<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;


final readonly class Extension implements \JsonSerializable, \Stringable, Dependency
{
    public function __construct(
        private DependencyName $name,
        private DependencyVersion $version
    ) {
    }

    public function name(): DependencyName
    {
        return $this->name;
    }

    public function version(): DependencyVersion
    {
        return $this->version;
    }

    public function __toString(): string
    {
        return mb_substr((string) $this->name, 4);
    }

    public function jsonSerialize(): array
    {
        return [ (string)  $this => (string) $this->version];
    }
}




