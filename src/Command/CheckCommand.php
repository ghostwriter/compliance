<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\CheckEvent;
use Ghostwriter\Compliance\Event\GenerateWorkflowFileEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class CheckCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Performs the QA job');
        $this->addArgument('job', InputArgument::REQUIRED, 'JSON string representing the job to run.');
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
        /** @var GenerateWorkflowFileEvent $checkEvent */
        $checkEvent =  $this->dispatcher->dispatch(new CheckEvent($this->dispatcher, $input, $this->symfonyStyle));

        return $checkEvent->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
