<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer;

use Ghostwriter\Compliance\Interface\Composer\DependencyInterface;
use Override;

use function mb_substr;

final readonly class Extension implements DependencyInterface
{
    public function __construct(
        private DependencyName $dependencyName,
        private DependencyVersion $dependencyVersion
    ) {}

    public static function new(DependencyName $dependencyName, DependencyVersion $dependencyVersion): self
    {
        return new self($dependencyName, $dependencyVersion);
    }

    #[Override]
    public function __toString(): string
    {
        return mb_substr((string) $this->dependencyName, 4);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            (string) $this => (string) $this->dependencyVersion,
        ];
    }

    #[Override]
    public function name(): DependencyName
    {
        return $this->dependencyName;
    }

    #[Override]
    public function version(): DependencyVersion
    {
        return $this->dependencyVersion;
    }
}
