<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Factory;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Override;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function getcwd;
use function implode;

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
        $_ENV['GITHUB_EVENT_NAME'] ??= 'compliance.command.matrix';
        $_ENV['GITHUB_EVENT_PATH'] ??= implode(DIRECTORY_SEPARATOR, ['tests', 'Fixture', 'payload.json']);
        $_ENV['GITHUB_TOKEN'] ??= 'github-token';
        $_ENV['GITHUB_WORKSPACE'] ??= getcwd();
        $_ENV['RUNNER_DEBUG'] ??= 0;

        return EnvironmentVariables::new();
    }
}
