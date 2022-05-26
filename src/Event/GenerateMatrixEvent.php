<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use function json_encode;

final class GenerateMatrixEvent extends AbstractEvent
{
    private array $matrix = [];

    public function getMatrix(): string
    {
        $result = json_encode($this->matrix, 0, 512);
        if (false === $result) {
            return '{}';
        }
        return $result;
    }

    public function setMatrix(array $matrix): void
    {
        $this->matrix[] = $matrix;
    }
}
