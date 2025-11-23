<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event;

use Ghostwriter\Container\Container;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Throwable;

final readonly class OutputEvent extends AbstractEvent
{
    public function __construct(
        private array|string $message,
        protected InputInterface $input,
        protected StyleInterface $style
    ) {}

    /** @throws Throwable */
    public static function new(array|string $message): self
    {
        return Container::getInstance()->build(self::class, [$message]);
    }

    public function getMessage(): array|string
    {
        return $this->message;
    }
}
