<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event;

use Ghostwriter\Compliance\Interface\EventDispatcher\EventInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\StyleInterface;

abstract readonly class AbstractEvent implements EventInterface
{
    public function __construct(
        protected InputInterface $input,
        protected StyleInterface $style
    ) {}

    public function input(): InputInterface
    {
        return $this->input;
    }

    public function output(): StyleInterface
    {
        return $this->style;
    }
}
