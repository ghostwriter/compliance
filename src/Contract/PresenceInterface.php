<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Contract;

use Symfony\Component\Finder\Finder;

interface PresenceInterface
{
    public function __construct(Finder $finder);

    public function isPresent(): bool;
}
