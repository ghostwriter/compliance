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
    public function __invoke(GenerateWorkflowFileEvent $generateWorkflowFileEvent): void
    {
        $dispatcher = $generateWorkflowFileEvent->getDispatcher();
        $input = $generateWorkflowFileEvent->getInput();

        $workflowPath = (string) $input->getArgument('workflow');

        $workflowPathExists = file_exists($workflowPath);
        $overwrite = (bool) $input->getOption('overwrite');

        if ($workflowPathExists && ! $overwrite) {
            $generateWorkflowFileEvent->stopPropagation();
            $dispatcher->dispatch(
                new OutputEvent($workflowPath . ' already exists; use "--overwrite|-o" to overwrite the workflow.')
            );
            return;
        }

        if ($workflowPathExists) {
            $dispatcher->dispatch(new OutputEvent($workflowPath . ' already exists, overwriting!'));
        }

        $workflowTemplatePath = dirname(__DIR__) . '/compliance.yml.dist';

        $result = file_put_contents($workflowPath, file_get_contents($workflowTemplatePath));

        if (false === $result) {
            $generateWorkflowFileEvent->stopPropagation();
            $dispatcher->dispatch(new OutputEvent($workflowPath . ' Failed to write data!'));
            return;
        }

        $dispatcher->dispatch(new OutputEvent($workflowPath . ' workflow generated!'));
    }
}
