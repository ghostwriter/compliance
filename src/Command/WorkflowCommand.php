<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\WorkflowEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'workflow', description: 'Creates a "automation.yml" workflow file.',)]
final class WorkflowCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->addArgument(
            'workflow',
            InputArgument::OPTIONAL,
            'Path to store the generated "automation.yml" workflow.',
            '.github/workflows/automation.yml'
        );

        $this->addOption(
            'overwrite',
            'o',
            InputOption::VALUE_NONE,
            'Path to store the generated "automation.yml" workflow.'
        );
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->dispatch(WorkflowEvent::class);
    }
}
