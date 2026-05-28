<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Symfony\Console;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\Console\Command\CheckCommand;
use Ghostwriter\Compliance\Console\Command\MatrixCommand;
use Ghostwriter\Compliance\Console\Command\RunCommand;
use Ghostwriter\Compliance\Console\Command\WorkflowCommand;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Container\PsrContainer;
use Override;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Throwable;

/**
 * @see ApplicationFactoryTest
 *
 * @implements FactoryInterface<Application>
 */
final readonly class ApplicationFactory implements FactoryInterface
{
    public const array COMMANDS = [
        'check' => CheckCommand::class,
        'matrix' => MatrixCommand::class,
        'run' => RunCommand::class,
        'workflow' => WorkflowCommand::class,
    ];

    /** @throws Throwable */
    #[Override]
    public function __invoke(ContainerInterface $container): Application
    {
        $application = new Application(
            name: 'Compliance',
            version: InstalledVersions::getPrettyVersion('ghostwriter/compliance')
        );

        $application->setAutoExit(false);

        $application->setCatchErrors(false);

        $application->setCatchExceptions(false);

        $application->setCommandLoader(new ContainerCommandLoader(
            $container->get(PsrContainer::class),
            self::COMMANDS
        ));

        $application->setDefaultCommand('run');

        return $application;
    }
}
