<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Ghostwriter\Console;

use Ghostwriter\Compliance\Container\Ghostwriter\Config\ConfigurationExtension;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Override;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Throwable;

use function class_exists;
use function is_a;
use function is_string;
use function sprintf;

/**
 * @see ApplicationExtensionTest
 *
 * @implements ExtensionInterface<Application>
 */
final readonly class ApplicationExtension implements ExtensionInterface
{
    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container, object $service): void
    {
        if (! $service instanceof Application) {
            return;
        }

        $configuration = $container->get(ConfigurationInterface::class)->wrap(ConfigurationExtension::class);
        $symfonyConsoleConfiguration = $configuration->wrap(
            'ghostwriter/console',
            [
                'name' => 'Console',
                'package' => 'ghostwriter/console',
                'auto_exit'       => false,
                'catch_errors'     => true,
                'catch_exceptions' => true,
                'commands'         => [],
                'default_command'  => 'help',
                'single_command'   => false,
            ]
        );

        $service->setAutoExit($symfonyConsoleConfiguration->get('auto_exit', false));
        $service->setCatchErrors($symfonyConsoleConfiguration->get('catch_errors', false));
        $service->setCatchExceptions($symfonyConsoleConfiguration->get('catch_exceptions', false));

        foreach ($symfonyConsoleConfiguration->get('commands', []) as $fullyQualifiedClassName) {
            if (! class_exists($fullyQualifiedClassName)) {
                throw new RuntimeException(sprintf(
                    'The command class "%s" does not exist.',
                    $fullyQualifiedClassName
                ));
            }

            if (! is_a($fullyQualifiedClassName, Command::class, true)) {
                throw new RuntimeException(sprintf(
                    'The command class "%s" does not extend "%s".',
                    $fullyQualifiedClassName,
                    Command::class
                ));
            }

            $service->add($container->get($fullyQualifiedClassName));
        }

        $defaultCommand = $symfonyConsoleConfiguration->get('default_command', null);
        if (! is_string($defaultCommand)) {
            return;
        }

        $singleCommand = $symfonyConsoleConfiguration->get('single_command', false);

        $service->setDefaultCommand($defaultCommand, true === $singleCommand);
    }
}
