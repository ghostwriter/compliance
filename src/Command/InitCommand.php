<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\GenerateWorkflowFileEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class InitCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Creates a "compliance.yml" workflow file.');
        $this->addArgument(
            'workflow',
            InputArgument::OPTIONAL,
            'Path to store the generated "compliance.yml" workflow.',
            '.github/workflows/compliance.yml'
        );

        $this->addOption(
            'overwrite',
            'o',
            InputOption::VALUE_NONE,
            'Path to store the generated compliance.yml workflow.'
        );
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
        $generateWorkflowFileEvent =  $this->dispatcher->dispatch(
            new GenerateWorkflowFileEvent($this->dispatcher, $input, $this->symfonyStyle)
        );

        return $generateWorkflowFileEvent->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
