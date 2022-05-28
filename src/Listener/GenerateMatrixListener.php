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
    public function __construct(private Container $container)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(GenerateMatrixEvent $event): void
    {
        foreach ($this->container->tagged(Tool::class) as $file) {
            /** @var ToolInterface $tool */
            $tool = $this->container->get($file);
            if ($tool->isPresent()) {
                /** @var int $phpVersion */
                $phpVersion = $this->container->get(ComposerDependency::CONFIG . '.php');
                $event->include(new Job($tool->name(), $tool->command(), ['latest', 'locked', 'lowest'], $phpVersion));
            }
        }
    }
}
