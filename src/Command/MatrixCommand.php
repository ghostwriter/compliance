<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\MatrixEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'matrix', description: 'Generates a job matrix for Github Actions.',)]
final class MatrixCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->addArgument('job', InputArgument::OPTIONAL, 'JSON string representing the job to run.');
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->dispatch(MatrixEvent::class);
    }
}
