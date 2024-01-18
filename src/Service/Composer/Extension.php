<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Ghostwriter\Compliance\Interface\Composer\DependencyInterface;
use function mb_substr;

final readonly class Extension implements DependencyInterface
{
    public function __construct(
        private DependencyName $name,
        private DependencyVersion $version
    ) {
    }

    public function __toString(): string
    {
        return mb_substr((string) $this->name, 4);
    }

    public function jsonSerialize(): array
    {
        return [
            (string) $this => (string) $this->version,
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
