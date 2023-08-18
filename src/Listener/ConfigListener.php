<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\ConfigEvent;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\ContainerInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Throwable;
use function realpath;

final class ConfigListener implements EventListenerInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly DispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ConfigEvent $configEvent): void
    {
        $input = $configEvent->getInput();

        $configPath = realpath((string) $input->getArgument('config'));

        $configPathExists = $configPath !== false;

        $overwrite = (bool) $input->getOption('overwrite');

        if ($configPathExists && ! $overwrite) {
            $configEvent->stopPropagation();

            $this->write($configPath . ' already exists; use "--overwrite|-o" to overwrite the configuration file.');
            return;
        }

        if ($configPathExists) {
            $this->write($configPath . ' already exists, overwriting!');
        }

        try {
            /** @var string $configTemplatePath */
            $configTemplatePath = $this->container->get(Compliance::TEMPLATE_CONFIG);
        } catch (Throwable $e) {
            $configEvent->stopPropagation();

            $this->write($e->getMessage());
            return;
        }

        $contents = file_get_contents($configTemplatePath);

        if ($contents === false) {
            $configEvent->stopPropagation();

            $this->write($configTemplatePath . ' Failed to read data!');
            return;
        }

        $result = file_put_contents($configPath, $contents);

        if ($result === false) {
            $configEvent->stopPropagation();

            $this->write($configPath . ' Failed to write data!');
            return;
        }

        $this->write($configPath . ' config generated!');
    }

    public function write(string $message): void
    {
        $this->dispatcher->dispatch(new OutputEvent($message));
    }
}
