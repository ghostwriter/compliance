<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use Ghostwriter\Compliance\Event\GitHubEvent;
use Symfony\Component\Console\Command\Command;

final readonly class SingleCommandApplicationDispatcher
{
    public function __construct(
        private DispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(
        InputInterface $input,
        OutputInterface $output
    ): int {
        return $this->dispatcher
            ->dispatch(new GitHubEvent($input, $output))
            ->isPropagationStopped() ? Command::FAILURE : Command::SUCCESS;
    }
}
