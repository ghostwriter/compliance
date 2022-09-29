<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Event\WorkflowEvent;
use Ghostwriter\Container\Container;
use Throwable;
use function realpath;

final class WorkflowListener implements EventListenerInterface
{
    public function __construct(
        private Container $container
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(WorkflowEvent $workflowEvent): void
    {
        $dispatcher = $workflowEvent->getDispatcher();
        $input = $workflowEvent->getInput();
        $workflowPath = realpath((string) $input->getArgument('workflow'));

        $workflowPathExists = false !== $workflowPath;

        $overwrite = (bool) $input->getOption('overwrite');

        if ($workflowPathExists && ! $overwrite) {
            $workflowEvent->stopPropagation();
            $dispatcher->dispatch(new OutputEvent(
                $workflowPath . ' already exists; use "--overwrite|-o" to overwrite the workflow file.'
            ));
            return;
        }

        if ($workflowPathExists) {
            $dispatcher->dispatch(new OutputEvent($workflowPath . ' already exists, overwriting!'));
        }

        $workflowTemplatePath = $this->container->get(Compliance::TEMPLATE_WORKFLOW);

        $result = file_put_contents($workflowPath, file_get_contents($workflowTemplatePath));

        if (false === $result) {
            $workflowEvent->stopPropagation();
            $dispatcher->dispatch(new OutputEvent($workflowPath . ' Failed to write data!'));
            return;
        }

        $dispatcher->dispatch(new OutputEvent($workflowPath . ' workflow generated!'));
    }
}
