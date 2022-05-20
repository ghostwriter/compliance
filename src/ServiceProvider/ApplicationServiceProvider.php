<?php

declare(strict_types=1);

namespace Ghostwriter\AutomatedCompliance\ServiceProvider;

use Composer\InstalledVersions;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Ghostwriter\EventDispatcher\Dispatcher;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

final class ApplicationServiceProvider implements ServiceProviderInterface
{
    public function __invoke(ContainerInterface $container): void
    {
        $container->bind(Application::class);
        $container->bind(DispatcherInterface::class, Dispatcher::class);

        $container->set('app.name', 'Automated Compliance');
        $container->set('app.package', InstalledVersions::getRootPackage()['name'] ?? 'ghostwriter/automated-compliance');
        $container->set('app.version', InstalledVersions::getPrettyVersion($container->get('app.package')));

        $container->extend(
            Application::class,
            static function ($container, $application): object {
                /** @var Application $application */
                $application->setAutoExit(false);
                $application->setCatchExceptions(false);

                foreach ($container->tagged(Command::class) as $command) {
                    $application->add($container->get($command));
                }

                return $application;
            }
        );
    }
}
