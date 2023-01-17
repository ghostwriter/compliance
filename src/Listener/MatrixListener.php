<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Contract\ToolInterface;
use Ghostwriter\Compliance\Event\MatrixEvent;
use Ghostwriter\Compliance\Option\ComposerDependency;
use Ghostwriter\Compliance\Option\Job;
use Ghostwriter\Compliance\Option\PhpVersion;
use Ghostwriter\Compliance\Option\Tool;
use Ghostwriter\Container\Container;
use Throwable;

final class MatrixListener implements EventListenerInterface
{
    /**
     * @var string[]
     */
    private const DEPENDENCIES = ['latest', 'locked', 'lowest'];

    public function __construct(
        private Container $container
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(MatrixEvent $generateMatrixEvent): void
    {
        $phpVersions = [PhpVersion::CURRENT_STABLE, PhpVersion::CURRENT_LATEST];
        /** @var ToolInterface $tool */
        foreach ($this->container->tagged(Tool::class) as $tool) {
            if ($tool->isPresent()) {

                foreach ($phpVersions as $phpVersion) {
                    foreach (self::DEPENDENCIES as $dependency) {
                        if ('latest' === $dependency) {
                            $generateMatrixEvent->include(
                                new Job($tool->name(), $tool->command(), $dependency, $phpVersion)
                            );
                        }
                        // Todo: support including/excluding $dependency
                        // $generateMatrixEvent->include(
                        //     new Job($tool->name(), $tool->command(), $dependency, $phpVersion)
                        // );
                    }
                }
            }
        }
    }
}
