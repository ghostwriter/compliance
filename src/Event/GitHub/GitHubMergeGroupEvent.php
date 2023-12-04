<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event\GitHub;

use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Ghostwriter\EventDispatcher\Trait\EventTrait;

final class GitHubMergeGroupEvent implements EventInterface
{
    use EventTrait;

    public function __construct(private string $content)
    {
    }
}