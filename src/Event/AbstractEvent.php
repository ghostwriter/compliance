<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Ghostwriter\EventDispatcher\Contract\EventInterface;
use Ghostwriter\EventDispatcher\Traits\EventTrait;
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
