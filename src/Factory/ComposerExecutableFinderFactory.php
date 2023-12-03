<?php

namespace Ghostwriter\Compliance\Factory;

use Ghostwriter\Compliance\Option\ComposerExecutableFinder;
use Ghostwriter\Compliance\Option\WhereExecutableFinder;
use Ghostwriter\Compliance\Service\Process;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use Throwable;

/**
 * @implements FactoryInterface<ComposerExecutableFinder>
 */
final readonly class ComposerExecutableFinderFactory implements FactoryInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container, array $arguments = []): ComposerExecutableFinder
    {
        return new ComposerExecutableFinder(
            $container->get(Process::class),
            $container->get(WhereExecutableFinder::class),
            PHP_OS_FAMILY === 'Windows'
        );
    }
}
