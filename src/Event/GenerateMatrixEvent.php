<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

final class GenerateMatrixEvent extends AbstractEvent
{
    private array $matrix = [];

    public function getMatrix(): array
    {
        return $this->matrix;
    }

    public function setMatrix(array $matrix): void
    {
        $this->matrix[] = $matrix;
    }
}
