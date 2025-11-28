<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Container\AutomationFactory;
use Ghostwriter\Compliance\Container\Ghostwriter\Config\ConfigurationExtension;
use Ghostwriter\Compliance\Container\Ghostwriter\EventDispatcher\ListenerProviderExtension;
use Ghostwriter\Config\Interface\ConfigurationInterface;
use Ghostwriter\Container\Interface\Service\DefinitionInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;

/**
 * @return array{
 *     'alias': array<class-string,class-string>,
 *     'define': array<class-string,class-string<DefinitionInterface>>,
 *     'extend': array<class-string,list<class-string<ExtensionInterface>>>,
 *     'factory': array<class-string,class-string<FactoryInterface>>
 * }
 */
return [
    'alias' => [],
    'define' => [],
    'extend' => [
        ConfigurationInterface::class => [ConfigurationExtension::class],
        ListenerProviderInterface::class => [ListenerProviderExtension::class],
    ],
    'factory' => [
        Automation::class => AutomationFactory::class,
    ],
];
