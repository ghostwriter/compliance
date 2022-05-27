<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Command\ChangeWorkingDirectory;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function getcwd;
use function getenv;

final class ChangeWorkingDirectoryEvent extends AbstractEvent
{
    /**
     * @throws Throwable
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        ChangeWorkingDirectory $command,
        SymfonyStyle $output
    ) {
        $input = new ArrayInput([
            '--current-working-directory' => getenv('GITHUB_WORKSPACE') ?: getcwd(),
        ], $command->getDefinition());

        parent::__construct($dispatcher, $input, $output);
    }
}
