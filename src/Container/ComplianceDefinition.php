<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container;

use Ghostwriter\Compliance\Container\Ghostwriter\Config\ConfigurationExtension;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\DefinitionInterface;
use Ghostwriter\Shell\Shell;
use Override;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function getcwd;

/**
 * @see ComplianceDefinitionTest
 */
final readonly class ComplianceDefinition implements DefinitionInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): void
    {
        $_ENV['GITHUB_EVENT_NAME'] ??= 'compliance.command.matrix';
        $_ENV['GITHUB_EVENT_PATH'] ??= 'tests' . DIRECTORY_SEPARATOR . 'fixture' . DIRECTORY_SEPARATOR . 'payload.json';
        $_ENV['GITHUB_TOKEN'] ??= 'github-token';
        $_ENV['GITHUB_WORKSPACE'] ??= getcwd();
        $_ENV['RUNNER_DEBUG'] ??= 1;

        $container->set(EnvironmentVariables::class, EnvironmentVariables::new());
        $container->set(Shell::class, Shell::new());

        $container->extend(ConfigurationInterface::class, ConfigurationExtension::class);

        $configuration = $container->get(ConfigurationInterface::class);

        $containerConfiguration = $configuration->wrap('ghostwriter/container');

        foreach ($containerConfiguration->get('alias', []) as $alias => $service) {
            $container->alias($service, $alias);
        }

        foreach ($containerConfiguration->get('define', []) as $definition) {
            $container->define($definition);
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
}
