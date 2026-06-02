<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Event;

use Ghostwriter\Compliance\GitHub\Action\Job;
use Ghostwriter\Compliance\GitHub\Action\Matrix;
use Ghostwriter\Compliance\Interface\EventDispatcher\EventInterface;
use Ghostwriter\Container\Container;
use Throwable;

final readonly class MatrixEvent implements EventInterface
{
    public function __construct(
        private Matrix $matrix,
    ) {}

    public static function new(): self
    {
        return Container::getInstance()->get(self::class);
    }

    public function exclude(array $matrices): void
    {
        $this->matrix->exclude($matrices);
    }

    /** @throws Throwable */
    public function getMatrix(): string
    {
        return $this->matrix->toString();
    }

    public function include(Job $job): void
    {
        $this->matrix->include($job);
    }
}
