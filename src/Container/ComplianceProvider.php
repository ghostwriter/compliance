<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container;

use Ghostwriter\Compliance\Automation;
use Ghostwriter\Compliance\Container\Ghostwriter\EventDispatcher\ListenerProviderExtension;
use Ghostwriter\Compliance\Container\Symfony\Console\ApplicationFactory;
use Ghostwriter\Compliance\Value\EnvironmentVariables;
use Ghostwriter\Container\Interface\BuilderInterface;
use Ghostwriter\Container\Interface\Service\ExtensionInterface;
use Ghostwriter\Container\Interface\Service\FactoryInterface;
use Ghostwriter\Container\Service\Provider\AbstractProvider;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;
use Override;
use Symfony\Component\Console\Application;
use Throwable;

use const DIRECTORY_SEPARATOR;

use function getcwd;
use function implode;

/**
 * @see ComplianceProviderTest
 */
final class ComplianceProvider extends AbstractProvider
{
    /**
     * alias => service.
     *
     * @var array<class-string,class-string>
     */
    public const array ALIAS = [];

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

    /** @throws Throwable */
    #[Override]
    public function register(BuilderInterface $builder): void
    {
        $_ENV['GITHUB_EVENT_NAME'] ??= 'compliance.command.matrix';
        $_ENV['GITHUB_EVENT_PATH'] ??= implode(DIRECTORY_SEPARATOR, ['tests', 'Fixture', 'payload.json']);
        $_ENV['GITHUB_TOKEN'] ??= 'github-token';
        $_ENV['GITHUB_WORKSPACE'] ??= getcwd();
        $_ENV['RUNNER_DEBUG'] ??= 1;

        parent::register($builder);
    }
}
