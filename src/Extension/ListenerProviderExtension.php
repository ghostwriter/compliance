<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Extension;

use Ghostwriter\Compliance\Listener\Debug;
use Ghostwriter\Compliance\Service\Finder;
use Ghostwriter\Config\Contract\ConfigInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Throwable;

/**
 * @implements ExtensionInterface<ListenerProvider>
 */
final readonly class ListenerProviderExtension implements ExtensionInterface
{
    public function __construct(
        private Finder $finder,
    ) {
    }
    /**
     * @param ListenerProvider $service
     *
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container, object $service): ListenerProvider
    {
        $config = $container->get(ConfigInterface::class);

        $debug = (bool) $config->get('app.debug', false);
        if ($debug) {
            $service->bind(EventInterface::class, Debug::class);
        }

        $files = $this->finder->findIn(dirname(__DIR__) . '/Listener/');
        foreach ($files as $file) {
            $path = $file->getPathname();

            if (str_contains($path, 'Abstract') || !str_ends_with($path, 'Listener.php')) {
                continue;
            }

            $service->bind(
                sprintf(
                    '%s%sEvent',
                    str_replace(
                        'Extension', 'Event', __NAMESPACE__ . '\\'
                    ),
                    $file->getBasename('Listener.php')
                ),
                sprintf(
                    '%s\%s',
                    str_replace(
                        'Extension', 'Listener', __NAMESPACE__
                    ),
                    $file->getBasename('.php')
                )
            );
        }

        return $service;
    }
}
