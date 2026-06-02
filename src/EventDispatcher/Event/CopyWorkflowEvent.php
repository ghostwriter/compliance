<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event;

use Ghostwriter\Compliance\Interface\EventDispatcher\EventInterface;

final readonly class CopyWorkflowEvent implements EventInterface
{
    private const string WORKFLOW_FILE = __DIR__ . '/../../automation.yml.dist';

    public function __construct(
        private string $to,
        private bool $overwrite,
    ) {}

    public static function new(string $to, bool $overwrite = false): self
    {
        return new self($to, $overwrite);
    }

    public function from(): string
    {
        return self::WORKFLOW_FILE;
    }

    public function overwrite(): bool
    {
        return $this->overwrite;
    }

    public function to(): string
    {
        return $this->to;
    }
}
