<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Contract\ToolInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Option\ComposerDependency;
use Ghostwriter\Compliance\Option\Job;
use Ghostwriter\Compliance\Option\Tool;
use Ghostwriter\Container\Container;
use Throwable;

final class MatrixListener implements EventListenerInterface
{
    /**
     * @var string[]
     */
    private const DEPENDENCIES = ['latest', 'locked', 'lowest'];

    public function __construct(private Container $container)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        foreach ($this->container->tagged(Tool::class) as $traversable) {
            /** @var ToolInterface $tool */
            $tool = $this->container->get($traversable);
            if ($tool->isPresent()) {
                /** @var int $phpVersion */
                $phpVersion = $this->container->get(ComposerDependency::CONFIG . '.php');
                foreach (self::DEPENDENCIES as $dependency) {
                    // Todo: support including/excluding $dependency
                    if ('latest' === $dependency) {
                        $generateMatrixEvent->include(
                            new Job($tool->name(), $tool->command(), $dependency, $phpVersion)
                        );
                    }
                }
            }
        }
    }
}
