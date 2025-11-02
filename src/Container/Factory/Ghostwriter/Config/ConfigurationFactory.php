<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Factory\Ghostwriter\Config;

use Ghostwriter\Config\Configuration;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use Override;
use Throwable;

/**
 * @see ConfigurationFactoryTest
 *
 * @implements FactoryInterface<Configuration>
 */
final readonly class ConfigurationFactory implements FactoryInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Configuration
    {
        return Configuration::new();
    }
}
