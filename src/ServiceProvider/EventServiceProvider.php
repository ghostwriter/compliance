<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Listener\Debug;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\ListenerProvider;
use Ghostwriter\EventDispatcher\ServiceProvider\EventDispatcherServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\Finder\Finder;
use function dirname;
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

                $finder = clone $container->get(Finder::class);

                $finder->files()
                    ->in(dirname(__DIR__) . '/Listener/')
                    ->name('*Listener.php')
                    ->notName('Abstract*.php')
                    ->sortByName();

                foreach ($finder->getIterator() as $splFileInfo) {
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
