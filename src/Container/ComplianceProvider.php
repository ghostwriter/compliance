<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container;

use Ghostwriter\Compliance\Container\Ghostwriter\Config\ConfigurationExtension;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\BuilderInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\ProviderInterface;
use Override;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function getcwd;
use function implode;

/**
 * @see ComplianceProviderTest
 */
final class ComplianceProvider implements ProviderInterface
{
    /** @throws Throwable */
    #[Override]
    public function boot(ContainerInterface $container): void
    {
        $configuration = $container->get(ConfigurationInterface::class);

        $containerConfiguration = $configuration->wrap('ghostwriter/container');

        foreach ($containerConfiguration->get('alias', []) as $alias => $service) {
            $container->alias($alias, $service);
        }

        foreach ($containerConfiguration->get('extend', []) as $service => $extensions) {
            foreach ($extensions as $extension) {
                $container->extend($service, $extension);
            }
        }

        foreach ($containerConfiguration->get('factory', []) as $service => $factory) {
            $container->factory($service, $factory);
        }
    }

    /** @throws Throwable */
    #[Override]
    public function register(BuilderInterface $builder): void
    {
        $_ENV['GITHUB_EVENT_NAME'] ??= 'compliance.command.matrix';
        $_ENV['GITHUB_EVENT_PATH'] ??= implode(DIRECTORY_SEPARATOR, ['tests', 'Fixture', 'payload.json']);
        $_ENV['GITHUB_TOKEN'] ??= 'github-token';
        $_ENV['GITHUB_WORKSPACE'] ??= getcwd();
        $_ENV['RUNNER_DEBUG'] ??= 1;

        $builder->extend(ConfigurationInterface::class, ConfigurationExtension::class);
    }
}
