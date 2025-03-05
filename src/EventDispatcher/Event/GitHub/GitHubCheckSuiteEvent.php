<?php

namespace Ghostwriter\Compliance\EventDispatcher\Event\GitHub;

use Ghostwriter\Compliance\EventDispatcher\Event\GitHubEventInterface;

/**
 * @implements GitHubEventInterface<bool>
 */
final readonly class GitHubCheckSuiteEvent implements GitHubEventInterface
{
    public function __construct(
        private string $content,
    ) {}

    #[Override]
    public function payload(): string
    {
        return $this->content;
    }
}
