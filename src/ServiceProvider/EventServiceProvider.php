<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Container\ContainerExceptionInterface;
use Ghostwriter\Container\ContainerInterface;
use Ghostwriter\Container\ServiceProviderInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Ghostwriter\EventDispatcher\Contract\EventInterface;
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
        $container->alias(ListenerProviderInterface::class, ListenerProvider::class);
        $container->alias(DispatcherInterface::class, Dispatcher::class);

        /** @param ListenerProvider $listenerProvider */
        $container->extend(
            ListenerProvider::class,
            static function (ContainerInterface $container, object $listenerProvider): ListenerProvider {
                $finder = clone $container->get(Finder::class);

                $finder->files()
                    ->in(dirname(__DIR__) . '/Listener/')
                    ->name('*Listener.php')
                    ->notName('Abstract*.php')
                    ->sortByName();

                foreach ($finder->getIterator() as $splFileInfo) {
                    /** @var class-string<EventInterface<bool>> $event */
                    $event = sprintf(
                        '%s%sEvent',
                        str_replace('ServiceProvider', 'Event', __NAMESPACE__ . '\\'),
                        $splFileInfo->getBasename('Listener.php')
                    );

                    /** @var callable-string $listener */
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
