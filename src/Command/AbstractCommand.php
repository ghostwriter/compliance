<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Command;

use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use function str_replace;
use function strtolower;

abstract class AbstractCommand extends Command
{
    public function __construct(protected DispatcherInterface $dispatcher, protected SymfonyStyle $symfonyStyle)
    {
        parent::__construct(self::getDefaultName());
    }

    public static function getDefaultName(): string
    {
        return strtolower(str_replace([__NAMESPACE__ . '\\', 'Command'], '', static::class));
    }
}
