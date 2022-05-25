<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\GenerateMatrixEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class MatrixCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Generates a job matrix for Github Actions.');
    }

    /**
     * Execute the command.
     *
     * @throws Throwable
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $generateMatrixEvent =  $this->dispatcher->dispatch(
            new GenerateMatrixEvent($this->dispatcher, $input, $this->output)
        );

        return $generateMatrixEvent->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
