<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event\GitHub;

use Ghostwriter\Compliance\Event\GitHubEventInterface;
use Ghostwriter\EventDispatcher\Trait\EventTrait;

/**
 * @template TStopped of bool
 *
 * @implements GitHubEventInterface<TStopped>
 */
final class GitHubMilestoneEvent implements GitHubEventInterface
{
    /** @use EventTrait<TStopped> */
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
