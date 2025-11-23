<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Container\AutomationFactory;
use Ghostwriter\Compliance\Container\ComplianceDefinition;
use Ghostwriter\Compliance\Container\Ghostwriter\Config\ConfigurationExtension;
use Ghostwriter\Compliance\Container\Ghostwriter\Console\ApplicationExtension;
use Ghostwriter\Compliance\Container\Ghostwriter\EventDispatcher\ListenerProviderExtension;
use Ghostwriter\Config\Configuration;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\Service\DefinitionInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\EventDispatcher\EventDispatcher;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Ghostwriter\Filesystem\Filesystem;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Json\Interface\JsonInterface;
use Ghostwriter\Json\Json;
use Ghostwriter\Shell\Interface\RunnerInterface;
use Ghostwriter\Shell\Interface\ShellInterface;
use Ghostwriter\Shell\Runner;
use Ghostwriter\Shell\Shell;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @return array{
 *     'alias': array<class-string,class-string>,
 *     'define': array<class-string,class-string<DefinitionInterface>>,
 *     'extend': array<class-string,list<class-string<ExtensionInterface>>>,
 *     'factory': array<class-string,class-string<FactoryInterface>>
 * }
 */
return [
    'alias' => [
        InputInterface::class => ArgvInput::class,
        ConfigurationInterface::class => Configuration::class,
        OutputInterface::class => ConsoleOutput::class,
        EventDispatcherInterface::class => EventDispatcher::class,
        FilesystemInterface::class => Filesystem::class,
        JsonInterface::class => Json::class,
        ListenerProviderInterface::class => ListenerProvider::class,
        OutputFormatterInterface::class => OutputFormatter::class,
        RunnerInterface::class => Runner::class,
        ShellInterface::class => Shell::class,
        StyleInterface::class => SymfonyStyle::class,
    ],
    'define' => [ComplianceDefinition::class],
    'extend' => [
        ConfigurationInterface::class => [ConfigurationExtension::class],
        ListenerProviderInterface::class => [ListenerProviderExtension::class],
        Application::class => [ApplicationExtension::class],
    ],
    'factory' => [
        Automation::class => AutomationFactory::class,
    ],
];
