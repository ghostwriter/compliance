<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Console\Command;

use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'matrix', description: 'Generates a job matrix for Github Actions.',)]
final class MatrixCommand extends AbstractCommand
{
    #[Override]
    protected function configure(): void
    {
        $this->addArgument('job', InputArgument::OPTIONAL, 'JSON string representing the job to run.');
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->dispatchClass(MatrixEvent::class);
    }
}
