<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Event\WorkflowEvent;
use Ghostwriter\Container\ContainerInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Throwable;
use function file_get_contents;
use function file_put_contents;
use function realpath;

final class WorkflowListener implements EventListenerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    public function __invoke(WorkflowEvent $workflowEvent): void
    {
        $input = $workflowEvent->getInput();
        $workflowPath = realpath((string) $input->getArgument('workflow'));

        $workflowPathExists = $workflowPath !== false;

        $overwrite = (bool) $input->getOption('overwrite');

        if ($workflowPathExists && ! $overwrite) {
            $workflowEvent->stopPropagation();
            $this->dispatcher->dispatch(new OutputEvent(
                $workflowPath . ' already exists; use "--overwrite|-o" to overwrite the workflow file.'
            ));
            return;
        }

        if ($workflowPathExists) {
            $this->dispatcher->dispatch(new OutputEvent($workflowPath . ' already exists, overwriting!'));
        }

        try {
            /** @var string $workflowTemplatePath */
            $workflowTemplatePath = $this->container->get(Compliance::TEMPLATE_WORKFLOW);
        } catch (Throwable $e) {
            $this->dispatcher->dispatch(new OutputEvent($e->getMessage()));
            return;
        }

        $workflowContents = file_get_contents($workflowTemplatePath);
        if ($workflowContents === false) {
            $workflowEvent->stopPropagation();
            $this->dispatcher->dispatch(new OutputEvent($workflowTemplatePath . ' Failed to read data!'));
            return;
        }

        $result = file_put_contents($workflowPath, $workflowContents);
        if ($result === false) {
            $workflowEvent->stopPropagation();
            $this->dispatcher->dispatch(new OutputEvent($workflowPath . ' Failed to write data!'));
            return;
        }

        $this->dispatcher->dispatch(new OutputEvent($workflowPath . ' workflow generated!'));
    }
}
