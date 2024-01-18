<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Factory;

use Ghostwriter\Compliance\Service\ComposerExecutableFinder;
use Ghostwriter\Compliance\Service\WhereExecutableFinder;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use const PHP_OS_FAMILY;

/**
 * @implements FactoryInterface<ComposerExecutableFinder>
 */
final readonly class ComposerExecutableFinderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, array $arguments = []): ComposerExecutableFinder
    {
        return new ComposerExecutableFinder(
            $container->get(WhereExecutableFinder::class),
            PHP_OS_FAMILY === 'Windows'
        );
    }
}
