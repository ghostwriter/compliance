<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Console\Command;

use Ghostwriter\Compliance\EventDispatcher\Event\CheckEvent;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'check', description: 'Performs the QA job',)]
final class CheckCommand extends AbstractCommand
{
    #[Override]
    protected function configure(): void
    {
        $this->addArgument('job', InputArgument::REQUIRED, 'JSON string representing the job to run.');
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->dispatchClass(CheckEvent::class);
    }
}
