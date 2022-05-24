<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\GenerateWorkflowFileEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function getcwd;

final class ChangeWorkingDirectoryCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Change current working directory');
        $this->addOption(
            'current-working-directory',
            'cwd',
            InputOption::VALUE_REQUIRED,
            'Path to the current working directory.',
            getcwd() ?: __DIR__
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
        return $this->dispatcher
            ->dispatch(new GenerateWorkflowFileEvent($this->dispatcher, $input, $this->output))
            ->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
