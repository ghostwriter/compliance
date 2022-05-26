<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use function basename;
use function json_encode;
use function sprintf;
use function str_split;
use const JSON_UNESCAPED_SLASHES;

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
        foreach ($matrices as $matrix){
            $this->matrix[] = $matrix;
        }
    }
}
