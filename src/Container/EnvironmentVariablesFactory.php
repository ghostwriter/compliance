<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container;

use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Throwable;

/**
 * @see EnvironmentVariablesFactoryTest
 *
 * @implements FactoryInterface<EnvironmentVariables>
 */
final readonly class EnvironmentVariablesFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): EnvironmentVariables
    {
        return EnvironmentVariables::new();
    }
}
