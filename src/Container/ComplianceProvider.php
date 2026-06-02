<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container;

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Container\Extension\ListenerProviderExtension;
use Ghostwriter\Compliance\Container\Factory\ApplicationFactory;
use Ghostwriter\Compliance\Container\Factory\AutomationFactory;
use Ghostwriter\Compliance\Container\Factory\EnvironmentVariablesFactory;
use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Container\Service\Provider\AbstractProvider;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Symfony\Component\Console\Application;

/**
 * @see ComplianceProviderTest
 */
final class ComplianceProvider extends AbstractProvider
{
    /**
     * service => [extension, ...].
     *
     * @var array<class-string,list<class-string<ExtensionInterface>>>
     */
    public const array EXTEND = [
        ListenerProviderInterface::class => [ListenerProviderExtension::class],
    ];

    /**
     * service => factory.
     *
     * @var array<class-string,class-string<FactoryInterface>>
     */
    public const array FACTORY = [
        Application::class => ApplicationFactory::class,
        Automation::class => AutomationFactory::class,
        EnvironmentVariables::class => EnvironmentVariablesFactory::class,
    ];
}
