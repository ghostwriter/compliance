<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\ConfigEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\Container;
use Throwable;
use function realpath;

final class ConfigListener implements EventListenerInterface
{
    public function __construct(
        private Container $container
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ConfigEvent $configEvent): void
    {
        $dispatcher = $configEvent->getDispatcher();
        $input = $configEvent->getInput();

        $configPath = realpath((string) $input->getArgument('config'));

        $configPathExists = $configPath !== false;

        $overwrite = (bool) $input->getOption('overwrite');

        if ($configPathExists && ! $overwrite) {
            $configEvent->stopPropagation();
            $dispatcher->dispatch(new OutputEvent(
                $configPath . ' already exists; use "--overwrite|-o" to overwrite the configuration file.'
            ));
            return;
        }

        if ($configPathExists) {
            $dispatcher->dispatch(new OutputEvent($configPath . ' already exists, overwriting!'));
        }

        /** @var string $configTemplatePath */
        $configTemplatePath = $this->container->get(Compliance::TEMPLATE_CONFIG);
        $contents = file_get_contents($configTemplatePath);
        $result = file_put_contents($configPath, $contents);

        if ($result === false) {
            $configEvent->stopPropagation();
            $dispatcher->dispatch(new OutputEvent($configPath . ' Failed to write data!'));
            return;
        }

        $dispatcher->dispatch(new OutputEvent($configPath . ' config generated!'));
    }
}
