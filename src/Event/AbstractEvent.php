<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\AbstractEvent as EventDispatcherAbstractEvent;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

abstract class AbstractEvent extends EventDispatcherAbstractEvent
{
    /**
     * @throws Throwable
     */
    public function __construct(
        protected DispatcherInterface $dispatcher,
        protected InputInterface $input,
        protected SymfonyStyle $symfonyStyle
    ) {
    }

    public function getDispatcher(): DispatcherInterface
    {
        return $this->dispatcher;
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function getOutput(): SymfonyStyle
    {
        return $this->symfonyStyle;
    }
}
