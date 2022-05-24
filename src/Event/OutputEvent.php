<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\AbstractEvent;

final class OutputEvent extends AbstractEvent
{
    private string $message;

    private string $type;

    public function __construct(string $message = 'default', string $type='info')
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
