<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\Factory\ComposerExecutableFinderFactory;
use Ghostwriter\Compliance\Service\ComposerExecutableFinder;
use Ghostwriter\Compliance\Service\Filesystem;
use Ghostwriter\Compliance\ToolInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use const DIRECTORY_SEPARATOR;
use function dirname;
use function is_a;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_replace;

final readonly class ApplicationServiceProvider implements ServiceProviderInterface
{
    public function __construct(
        private Filesystem $filesystem,
    ) {
    }

    public function __invoke(ContainerInterface $container): void
    {
        $container->set(EnvironmentVariables::class, EnvironmentVariables::new());

        $container->provide(EventServiceProvider::class);
        $container->provide(ConsoleServiceProvider::class);
        $container->provide(ConfigServiceProvider::class);

        // $this->registerServiceProviders($container);
        $this->registerTools($container);

        $container->factory(ComposerExecutableFinder::class, ComposerExecutableFinderFactory::class);
    }

    private function registerTools(ContainerInterface $container): void
    {
        // Tag the CLI tools we support.
        foreach ($this->filesystem->findIn(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Tool') as $file) {
            $path = $file->getPathname();
            if (str_contains($path, 'Abstract')) {
                continue;
            }

            if (! str_ends_with($path, '.php')) {
                continue;
            }

            $service = sprintf(
                '%s%s',
                str_replace('ServiceProvider', 'Tool', __NAMESPACE__ . '\\'),
                $file->getBasename('.php')
            );

            if (! is_a($service, ToolInterface::class, true)) {
                continue;
            }

            $container->tag($service, [ToolInterface::class]);
        }
    }
}
