<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

final class OutputEvent extends AbstractEvent
{
    public function __construct(
        private readonly array|string $message
    ) {
    }

    public function getMessage(): array|string
    {
        return $this->message;
    }
}
