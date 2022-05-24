<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\ChangeWorkingDirectoryEvent;
use Throwable;
use function chdir;
use function error_get_last;
use function sprintf;

final class ChangeWorkingDirectoryListener implements EventListenerInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(ChangeWorkingDirectoryEvent $event): void
    {
        $input = $event->getInput();
        $output = $event->getOutput();

        /** @var string $currentWorkingDirectory */
        $currentWorkingDirectory = $input->getOption('current-working-directory');

        $result = @chdir($currentWorkingDirectory);

        if (false === $result) {
            $event->stopPropagation();
            $output->error(sprintf(
                'Unable to change current working directory; %s; "%s" given.',
                error_get_last()['message'] ?? 'No such file or directory',
                $currentWorkingDirectory
            ));
            return;
        }

        $output->comment(sprintf('Changed current working directory to "%s".', $currentWorkingDirectory));
    }
}
