<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

final class OutputEvent extends AbstractEvent
{
    public function __construct(
        private readonly string|array $message
    ) {
    }

    public function getMessage(): string|array
    {
        return $this->message;
    }
}
