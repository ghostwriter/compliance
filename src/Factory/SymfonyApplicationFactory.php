<?php

namespace Ghostwriter\Compliance\Factory;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Config\Contract\ConfigInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\FactoryInterface;
use RuntimeException;
use Symfony\Component\Console\Application as SymfonyApplication;
use Throwable;

/**
 * @implements FactoryInterface<SymfonyApplication>
 */
final readonly class SymfonyApplicationFactory implements FactoryInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container, array $arguments = []): SymfonyApplication
    {
        return new SymfonyApplication(
            Compliance::NAME,
                InstalledVersions::getPrettyVersion(Compliance::PACKAGE) ??
            throw new RuntimeException('Unable to determine version!')
        );
    }
}
