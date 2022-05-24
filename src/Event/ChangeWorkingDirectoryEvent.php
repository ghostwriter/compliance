<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Command\ChangeWorkingDirectoryCommand;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function getcwd;

final class ChangeWorkingDirectoryEvent extends AbstractEvent
{
    /**
     * @throws Throwable
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        ChangeWorkingDirectoryCommand $command,
        SymfonyStyle $output
    )
    {
        $input = new ArrayInput([
            '--current-working-directory' => getcwd(),
        ], $command->getDefinition());

        parent::__construct($dispatcher, $input, $output);
    }
}
