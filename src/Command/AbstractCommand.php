<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function mb_strtolower;
use function sprintf;
use function str_replace;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected ContainerInterface $container,
        protected DispatcherInterface $dispatcher,
        protected SymfonyStyle $symfonyStyle
    ) {
        parent::__construct(static::getDefaultName());
    }

    /**
     *
     * @param class-string<EventInterface<bool>> $event
     *
     * @throws Throwable
     *
     * @return int 0 if everything went fine, or an exit code
     */
    public function dispatch(string $event): int
    {
        return $this->dispatcher->dispatch(
            $this->container->build($event)
        )->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }

    public static function getDefaultName(): string
    {
        return mb_strtolower(str_replace([__NAMESPACE__ . '\\', 'Command'], '', static::class));
    }

    /**
     * @throws Throwable
     */
    public function write(string $message): int
    {
        return $this->dispatcher->dispatch(
            new OutputEvent([
                '::echo::on',
                sprintf('::group::%s %s', Compliance::NAME, Compliance::BLACK_LIVES_MATTER),
                $message,
                '::endgroup::',
                '::echo::off',
            ])
        )->isPropagationStopped() ? self::FAILURE : self::SUCCESS;
    }
}
