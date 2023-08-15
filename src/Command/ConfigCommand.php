<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Event\ConfigEvent;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class ConfigCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setDescription('Creates a "compliance.php" file.');
        $this->addArgument(
            'config',
            InputArgument::OPTIONAL,
            'Path to store the generated "compliance.php" workflow.',
            'compliance.php'
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
        return $this->dispatch(new ConfigEvent($input, $this->symfonyStyle));
    }
}
