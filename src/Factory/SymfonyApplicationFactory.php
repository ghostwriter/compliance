<?php

namespace Ghostwriter\Compliance\Factory;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\Compliance;
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
//        $config = $container->get(ConfigInterface::class);
// later we'll connect the config file, lets first get the app running

//        $parameters = [
//            $config->get('app.name', Compliance::NAME),
//            $config->get('app.version',
//
//            ), ...$arguments
//        ];
        // Woot! This works!


//        Lets extract the factory implementation from the vendor directory into the actual project.
        return new SymfonyApplication(
            Compliance::NAME,
                InstalledVersions::getPrettyVersion(Compliance::PACKAGE) ??
            throw new RuntimeException('Unable to determine version!')
        );
    }
}
