<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Interface\EventDispatcher\Event;

use Ghostwriter\Compliance\Interface\EventDispatcher\EventInterface;

interface GitHubEventInterface extends EventInterface
{
    public function payload(): string;
}
