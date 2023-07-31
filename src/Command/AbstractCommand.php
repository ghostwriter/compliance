<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\ContainerInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Ghostwriter\EventDispatcher\Contract\EventInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function sprintf;
use function str_replace;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected ContainerInterface $container,
        protected DispatcherInterface $dispatcher,
        protected SymfonyStyle $symfonyStyle
    ) {
        parent::__construct(self::getDefaultName());
    }

    /**
     * @throws Throwable
     *
     * @return int 0 if everything went fine, or an exit code
     */
    public function dispatch(EventInterface $event): int
    {
        return $this->dispatcher
            ->dispatch($event)
            ->isPropagationStopped()
            ? self::FAILURE
            : self::SUCCESS;
    }

    public static function getDefaultName(): string
    {
        return mb_strtolower(str_replace([__NAMESPACE__ . '\\', 'Command'], '', static::class));
    }

    public function write(string $message): int
    {
        return $this->dispatch(
            new OutputEvent([
                '::echo::on',
                sprintf('::group::%s %s', Compliance::NAME, Compliance::BLACK_LIVES_MATTER),
                $message,
                '::endgroup::',
                '::echo::off',
            ])
        );
    }
}
