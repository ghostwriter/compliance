<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\Interface\EventInterface;

/**
 * @template T of bool
 *
 * @extends EventInterface<T>
 */
interface GitHubEventInterface extends EventInterface
{
    public function payload(): string;
}
