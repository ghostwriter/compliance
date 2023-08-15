<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

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
}
