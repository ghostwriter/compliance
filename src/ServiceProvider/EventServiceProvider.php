<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Listener\Debug;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Ghostwriter\EventDispatcher\ServiceProvider\EventDispatcherServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function dirname;
use function preg_match;
use function sprintf;
use function str_replace;

final class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): void
    {
        $container->build(EventDispatcherServiceProvider::class);

        $container->extend(
            ListenerProvider::class,
            static function (ContainerInterface $container, object $listenerProvider): ListenerProvider {
                /** @var ListenerProvider $listenerProvider */
                // $listenerProvider->addListener($container->get(Debug::class), 0, 'object');

                /** @var SplFileInfo $splFileInfo */
                foreach (
                    new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator(dirname(__DIR__) . '/Listener/')
                    ) as $splFileInfo
                ) {
                    /** @var string $path */
                    $path = $splFileInfo->getRealPath();

                    if (! preg_match('#Listener\.php$#', $path)) {
                        continue;
                    }

                    $event = sprintf(
                        '%s%sEvent',
                        str_replace('ServiceProvider', 'Event', __NAMESPACE__ . '\\'),
                        $splFileInfo->getBasename('Listener.php')
                    );

                    $listener =  sprintf(
                        '%s%s',
                        str_replace('ServiceProvider', 'Listener', __NAMESPACE__ . '\\'),
                        $splFileInfo->getBasename('.php')
                    );

                    $listenerProvider->addListener($container->get($listener), 0, $event);
                }

                return $listenerProvider;
            }
        );
    }
}
