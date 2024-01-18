<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event\GitHub;

use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\EventDispatcher\Trait\EventTrait;

final class GitHubCreateEvent implements GitHubEventInterface
{
    use EventTrait;

    public function __construct(
        private string $content
    ) {
    }

    public function payload(): string
    {
        return $this->content;
    }
}
