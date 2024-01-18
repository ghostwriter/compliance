<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Extension;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Ghostwriter\Compliance\Listener\Debug;
use Ghostwriter\Compliance\Service\Filesystem;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use const DIRECTORY_SEPARATOR;
use function dirname;
use function is_a;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_replace;

/**
 * @implements ExtensionInterface<ListenerProvider>
 */
final readonly class ListenerProviderExtension implements ExtensionInterface
{
    public function __construct(
        private EnvironmentVariables $environmentVariables,
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @param ListenerProvider $service
     */
    public function __invoke(ContainerInterface $container, object $service): ListenerProvider
    {
        if ($this->environmentVariables->get('GITHUB_DEBUG', '0') === '1') {
            $service->bind(EventInterface::class, Debug::class);
        }

        foreach ($this->filesystem->findIn(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Listener') as $file) {
            $path = $file->getPathname();

            $skip = match (true) {
                default => false,
                ! str_ends_with($path, '.php'),
                str_contains($path, 'Abstract'),
                str_ends_with($path, 'Trait.php') => true,
            };

            if ($skip) {
                continue;
            }

            $listener = sprintf(
                '%s\%s',
                str_replace('Extension', 'Listener', __NAMESPACE__),
                $file->getBasename('.php')
            );

            if ($listener === Debug::class) {
                continue;
            }

            if (! is_a($listener, EventListenerInterface::class, true)) {
                continue;
            }

            $service->listen($listener);
        }

        return $service;
    }
}
