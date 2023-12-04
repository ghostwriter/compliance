<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Ghostwriter\EventDispatcher\Trait\EventTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

/**
 * @implements EventInterface<bool>
 */
abstract class AbstractEvent implements EventInterface
{
    use EventTrait;

    /**
     * @throws Throwable
     */
    public function __construct(
        protected readonly DispatcherInterface $dispatcher,
        protected readonly InputInterface $input,
        protected readonly SymfonyStyle $symfonyStyle
    ) {
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function getOutput(): SymfonyStyle
    {
        return $this->symfonyStyle;
    }

    public function getDispatcher(): DispatcherInterface
    {
        return $this->dispatcher;
    }
    /**
     * @return EventInterface<bool>
     * @param EventInterface<bool> $event
     */
    public function dispatch(EventInterface $event): EventInterface
    {
        return $this->dispatcher->dispatch($event);
    }
}
