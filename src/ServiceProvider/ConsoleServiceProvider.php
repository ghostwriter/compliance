<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Style\SymfonyStyle;
use function dirname;
use function preg_match;
use function sprintf;
use function str_replace;
use function strtolower;

final class ConsoleServiceProvider implements ServiceProviderInterface
{
    public function __invoke(ContainerInterface $container): void
    {
        $container->bind(Compliance::class);
        $container->alias(SymfonyApplication::class, Compliance::class);
        // Input
        $container->bind(ArgvInput::class);
        $container->bind(ArrayInput::class);
        $container->bind(StringInput::class);
        $container->alias(Input::class, ArgvInput::class);
        $container->alias(InputInterface::class, Input::class);
        // Output
        $container->bind(ConsoleOutput::class);
        $container->bind(NullOutput::class);
        $container->bind(SymfonyStyle::class);
        $container->alias(Output::class, ConsoleOutput::class);
        $container->alias(OutputInterface::class, Output::class);
        // Commands
        /** @var SplFileInfo $splFileInfo */
        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(dirname(__DIR__) . '/Command/')
            ) as $splFileInfo
        ) {
            /** @var string $path */
            $path = $splFileInfo->getPathname();

            if (! preg_match('#Command\.php$#', $path)) {
                continue;
            }

            if (str_contains(strtolower($path), 'abstract')) {
                continue;
            }

            $command = sprintf(
                '%s%s',
                str_replace('ServiceProvider', 'Command', __NAMESPACE__ . '\\'),
                $splFileInfo->getBasename('.php')
            );

            $container->bind($command, $command, [Command::class]);
        }
    }
}
