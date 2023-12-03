<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Extension\SymfonyApplicationExtension;
use Ghostwriter\Compliance\Factory\SymfonyApplicationFactory;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final readonly class ConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container): void
    {
        $container->alias(InputInterface::class, ArgvInput::class);
        $container->alias(OutputInterface::class, ConsoleOutput::class);
        $container->alias(OutputFormatterInterface::class, OutputFormatter::class);
        $container->extend(Application::class, SymfonyApplicationExtension::class);
        $container->factory(Application::class, SymfonyApplicationFactory::class);
    }
}
