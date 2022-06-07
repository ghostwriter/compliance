<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use const PHP_EOL;
use function sprintf;

final class MatrixCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Generates a job matrix for Github Actions.');
        $this->addArgument('job', InputArgument::OPTIONAL, 'JSON string representing the job to run.');
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
            new MatrixEvent($this->dispatcher, $input, $this->symfonyStyle)
        );

        $this->write(
            (
                $this->container->has(Compliance::PATH_CONFIG) ?
                'Registered config path: ' . $this->container->get(Compliance::PATH_CONFIG) . PHP_EOL
                : ''
            ) . sprintf('::set-output name=matrix::%s', $generateMatrixEvent->getMatrix())
        );

        return $generateMatrixEvent->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
