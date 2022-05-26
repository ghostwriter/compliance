<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Contract;

interface PresenceInterface
{
    public function isPresent(): bool;
}
