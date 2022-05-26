<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use const JSON_UNESCAPED_SLASHES;
use function json_encode;

final class GenerateMatrixEvent extends AbstractEvent
{
    private array $matrix = [];

    public function getMatrix(): string
    {
        $result = json_encode($this->matrix, JSON_UNESCAPED_SLASHES);
        if (false === $result) {
            return '{}';
        }
        return $result;
    }

    public function setMatrix(array $matrices): void
    {
        /** @var string $matrix */
        foreach ($matrices as $matrix) {
            $this->matrix[] = $matrix;
        }
    }
}
