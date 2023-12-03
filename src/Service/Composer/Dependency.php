<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use JsonSerializable;
use Stringable;

interface Dependency extends JsonSerializable, Stringable
{
    public function __toString(): string;
    public function jsonSerialize(): array;
    public function name(): DependencyName;
    public function version(): DependencyVersion;
}