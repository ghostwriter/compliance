<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\AbstractEvent;

final class OutputEvent extends AbstractEvent
{
    public function __construct(
        private string|array $message
    ) {
    }

    public function getMessage(): string|array
    {
        return $this->message;
    }
}
