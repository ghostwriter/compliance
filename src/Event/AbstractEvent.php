<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\AbstractEvent as EventDispatcherAbstractEvent;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

/**
 * @template TPropagationStopped of bool
 * @extends EventDispatcherAbstractEvent<TPropagationStopped>
 */
abstract class AbstractEvent extends EventDispatcherAbstractEvent
{
    protected DispatcherInterface $dispatcher;

    protected InputInterface $input;

    protected SymfonyStyle $output;

    /**
     * @throws Throwable
     */
    public function __construct(DispatcherInterface $dispatcher, InputInterface $input, SymfonyStyle $output)
    {
        $this->dispatcher = $dispatcher;
        $this->input = $input;
        $this->output = $output;
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
        return $this->output;
    }
}
