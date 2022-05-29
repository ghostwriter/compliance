<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Contract\ToolInterface;
use Ghostwriter\Compliance\Event\GenerateMatrixEvent;
use Ghostwriter\Compliance\ValueObject\ComposerDependency;
use Ghostwriter\Compliance\ValueObject\Job;
use Ghostwriter\Compliance\ValueObject\Tool;
use Ghostwriter\Container\Container;
use Throwable;

final class GenerateMatrixListener implements EventListenerInterface
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
    public function __invoke(GenerateMatrixEvent $generateMatrixEvent): void
    {
        foreach ($this->container->tagged(Tool::class) as $traversable) {
            /** @var ToolInterface $tool */
            $tool = $this->container->get($traversable);
            if ($tool->isPresent()) {
                /** @var int $phpVersion */
                $phpVersion = $this->container->get(ComposerDependency::CONFIG . '.php');
                foreach (self::DEPENDENCIES as $dependency) {
                    // Todo: support including/excluding $dependency
                    if($dependency === 'latest')
                    {
                        $generateMatrixEvent->include(new Job($tool->name(), $tool->command(), $dependency, $phpVersion));
                    }
                }
            }
        }
    }
}
