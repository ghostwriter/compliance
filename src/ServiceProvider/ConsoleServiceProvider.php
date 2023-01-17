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

final class ConsoleServiceProvider implements ServiceProviderInterface
{
    public function __invoke(ContainerInterface $container): void
    {
        $container->bind(Compliance::class);
        $container->alias(Compliance::class, SymfonyApplication::class);
        // Input
        $container->bind(ArgvInput::class);
        $container->bind(ArrayInput::class);
        $container->bind(StringInput::class);
        $container->alias(ArgvInput::class, Input::class);
        $container->alias(Input::class, InputInterface::class);
        // Output
        $container->bind(ConsoleOutput::class);
        $container->bind(NullOutput::class);
        $container->bind(SymfonyStyle::class);
        $container->alias(ConsoleOutput::class, Output::class);
        $container->alias(Output::class, OutputInterface::class);
        // Commands
        /** @var SplFileInfo $splFileInfo */
        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(dirname(__DIR__) . '/Command/')
            ) as $splFileInfo
        ) {
            $path = $splFileInfo->getPathname();

            if (! preg_match('#Command\.php$#', $path)) {
                continue;
            }

            if (str_contains(mb_strtolower($path), 'abstract')) {
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
