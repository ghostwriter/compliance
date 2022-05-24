<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\GenerateWorkflowFileEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Throwable;
use function dirname;
use function file_exists;

final class GenerateWorkflowFileListener implements EventListenerInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(GenerateWorkflowFileEvent $event): void
    {
        $dispatcher = $event->getDispatcher();
        $input = $event->getInput();

        $workflowPath = (string) $input->getArgument('workflow');

        $workflowPathExists = file_exists($workflowPath);
        $overwrite = (bool) $input->getOption('overwrite');

        if ($workflowPathExists && ! $overwrite) {
            $event->stopPropagation();
            $dispatcher->dispatch(
                new OutputEvent(
                    $workflowPath . ' already exists; use "--overwrite|-o" to overwrite the workflow.',
                    'error'
                )
            );
            return;
        }

        if ($workflowPathExists) {
            $dispatcher->dispatch(new OutputEvent($workflowPath . ' already exists, overwriting!', 'warning'));
        }

        $workflowTemplatePath = dirname(__DIR__) . '/compliance.yml.dist';

        $result = file_put_contents($workflowPath, file_get_contents($workflowTemplatePath));

        if (false === $result) {
            $event->stopPropagation();
            $dispatcher->dispatch(new OutputEvent($workflowPath . ' Failed to write data!', 'warning'));
            return;
        }

        $dispatcher->dispatch(new OutputEvent($workflowPath . ' workflow generated!', 'success'));
    }
}
