<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Ghostwriter\Compliance\Interface\Composer\DependencyInterface;
use Ghostwriter\Json\Json;

final readonly class Package implements DependencyInterface
{
    public function __construct(
        private DependencyName $name,
        private DependencyVersion $version
    ) {
    }

    public function __toString(): string
    {
        return (new Json())->encode($this);
    }

    public function jsonSerialize(): array
    {
        return [
            $this->name->__toString() => $this->version->__toString(),
        ];
    }

    public function name(): DependencyName
    {
        return $this->name;
    }

    public function version(): DependencyVersion
    {
        return $this->version;
    }

    public static function new(DependencyName $dependencyName, DependencyVersion $dependencyVersion): self
    {
        return new self($dependencyName, $dependencyVersion);
    }
}
