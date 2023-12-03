<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Ghostwriter\Compliance\Service\Composer\Version;
use Ghostwriter\Json\Json;

final readonly class Package implements \JsonSerializable, \Stringable, Dependency
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
        return Json::encode($this);
    }

    public function jsonSerialize(): array
    {
        return [$this->name => $this->version];
    }
}