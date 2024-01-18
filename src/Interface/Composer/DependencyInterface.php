<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface\Composer;

use Ghostwriter\Compliance\Service\Composer\DependencyName;
use Ghostwriter\Compliance\Service\Composer\DependencyVersion;
use JsonSerializable;
use Stringable;

interface DependencyInterface extends JsonSerializable, Stringable
{
    public function __toString(): string;

    public function jsonSerialize(): array;

    public function name(): DependencyName;

    public function version(): DependencyVersion;
}
