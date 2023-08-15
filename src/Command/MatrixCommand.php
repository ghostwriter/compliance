<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Environment\Environment;
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
        /** @var MatrixEvent $matrixEvent */
        $matrixEvent =  $this->dispatcher->dispatch(
            new MatrixEvent($input, $this->symfonyStyle)
        );

        $environment = new Environment();

        $matrix = sprintf('matrix=%s' . PHP_EOL, $matrixEvent->getMatrix());
        file_put_contents(
            $environment->getServerVariable('GITHUB_OUTPUT', tempnam(sys_get_temp_dir(), 'GITHUB_OUTPUT')),
            $matrix,
            FILE_APPEND
        );

        $gitHubOutput = $this->container->has(Compliance::PATH_CONFIG) ?
            'Registered config path: ' . $this->container->get(Compliance::PATH_CONFIG) . PHP_EOL . PHP_EOL :
            '';

        $this->write($gitHubOutput . $matrix);

        return $matrixEvent->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
