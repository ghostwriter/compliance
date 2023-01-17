<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Container\Contract\ContainerExceptionInterface;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Ghostwriter\EventDispatcher\Contract\ListenerProviderInterface;
use Ghostwriter\EventDispatcher\Dispatcher;
use Ghostwriter\EventDispatcher\ListenerProvider;
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
        $container->bind(ListenerProvider::class);
        $container->alias(ListenerProvider::class, ListenerProviderInterface::class);

        $container->set(Dispatcher::class, static fn (ContainerInterface $container): object => $container->build(
            Dispatcher::class,
            [
                'listenerProvider' => $container->get(ListenerProviderInterface::class),
            ]
        ));
        $container->alias(Dispatcher::class, DispatcherInterface::class);

        /** @param ListenerProvider $listenerProvider */
        $container->extend(
            ListenerProvider::class,
            static function (ContainerInterface $container, object $listenerProvider): ListenerProvider {
                /** @var ListenerProvider $listenerProvider */
                $finder = $container->build(Finder::class);

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
                        "%s\%s",
                        str_replace('ServiceProvider', 'Listener', __NAMESPACE__),
                        $splFileInfo->getBasename('.php')
                    );

                    $listenerProvider->bindListener($event, $listener);
                }

                return $listenerProvider;
            }
        );
    }
}
