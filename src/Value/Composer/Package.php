<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Ghostwriter\Compliance\Interface\Composer\DependencyInterface;
use Ghostwriter\Json\Interface\JsonInterface;
use Override;
use Throwable;

final readonly class Package implements DependencyInterface
{
    public function __construct(
        private DependencyName $dependencyName,
        private DependencyVersion $dependencyVersion,
        private JsonInterface $json
    ) {}

    public static function new(
        DependencyName $dependencyName,
        DependencyVersion $dependencyVersion,
        JsonInterface $json
    ): self {
        return new self($dependencyName, $dependencyVersion, $json);
    }

    /** @throws Throwable */
    #[Override]
    public function __toString(): string
    {
        return $this->json->encode($this);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            $this->dependencyName->__toString() => $this->dependencyVersion->__toString(),
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
