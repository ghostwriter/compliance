<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Console\Command;

use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\EventDispatcherInterface;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

use function mb_strtolower;
use function str_replace;

abstract class AbstractCommand extends Command
{
    public function __construct(
        protected ContainerInterface $container,
        protected EventDispatcherInterface $eventDispatcher,
        protected FilesystemInterface $filesystem,
        protected SymfonyStyle $symfonyStyle,
    ) {
        parent::__construct(static::getDefaultName());
    }

    /**
     * @param class-string $event
     *
     * @return int 0 if everything went fine, or an exit code
     */
    public function dispatchClass(string $event): int
    {
        return $this->dispatch($this->container->get($event));
    }

    /**
     * @param class-string $event
     *
     * @return int 0 if everything went fine, or an exit code
     */
    public function dispatch(object $event): int
    {
        try {
            $this->eventDispatcher->dispatch($event);
        } catch (\Throwable $throwable) {
            $this->symfonyStyle->error(sprintf("[%s] %s", $throwable::class, $throwable->getMessage()));

            $this->symfonyStyle->error($throwable->getTraceAsString());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    #[Override]
    public static function getDefaultName(): string
    {
        return mb_strtolower(str_replace([__NAMESPACE__ . '\\', 'Command'], '', static::class));
    }
}
