<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\WorkflowEvent;
use Ghostwriter\Config\Contract\ConfigInterface;
use Throwable;
use function file_get_contents;
use function file_put_contents;

final readonly class WorkflowListener implements EventListenerInterface
{
    public function __construct(
        private ConfigInterface $config,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(WorkflowEvent $workflowEvent): void
    {
        $input = $workflowEvent->getInput();

        $workflowPath = sprintf(
            '%s/%s',
            $this->config->get(Compliance::CURRENT_WORKING_DIRECTORY),
            $input->getArgument('workflow')
        );

        $workflowPathExists = file_exists($workflowPath);

        $overwrite = (bool) $input->getOption('overwrite');

        if ($workflowPathExists && ! $overwrite) {
            $workflowEvent->stopPropagation();
            error(
                sprintf(
                    '%s already exists; use "--overwrite|-o" to overwrite the workflow file.',
                    $workflowPath
                ),
                __FILE__,
                __LINE__
            );
            return;
        }

        if ($workflowPathExists) {
            warning(
                $workflowPath . ' already exists, overwriting!',
                __FILE__,
                __LINE__
            );
        }

        try {
            /** @var string $workflowTemplatePath */
            $workflowTemplatePath = $this->config->get(Compliance::WORKFLOW_TEMPLATE,);
        } catch (Throwable $e) {
            error(
                $e->getMessage(),
                __FILE__,
                __LINE__
            );
            return;
        }

        $workflowContents = file_get_contents($workflowTemplatePath);
        if ($workflowContents === false) {
            $workflowEvent->stopPropagation();
            error(
                $workflowTemplatePath . ' Failed to read data!',
                __FILE__,
                __LINE__
            );
            return;
        }

        // these can also be event listeners
        $result = file_put_contents($workflowPath, $workflowContents);
        if ($result === false) {
            $workflowEvent->stopPropagation();
            error(
                $workflowPath . ' Failed to write data!',
                __FILE__,
                __LINE__
            );
            return;
        }

        dispatchOutputEvent($workflowPath . ' workflow generated!');
    }
}
