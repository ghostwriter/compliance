<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\ValueObject\Job;
use function json_encode;

final class GenerateMatrixEvent extends AbstractEvent
{
    /**
     * @var array{
     *     exclude:list<string>,
     *     include:list<array{
     *     name:string,
     *     command:string,
     *     os:string,
     *     php:string,
     *     dependency:string,
     *     experimental:bool
     * }>,
     * }
     */
    private array $matrix = [
        'include' => [],
        'exclude' => [],
    ];

    public function exclude(array $matrices): void
    {
        /** @var string $matrix */
        foreach ($matrices as $matrix) {
            $this->matrix['exclude'][] = $matrix;
        }
    }

    public function getMatrix(): string
    {
        $result = json_encode($this->matrix);
        if (false === $result) {
            return '{}';
        }
        return $result;
    }

    public function include(Job $job): void
    {
        $this->matrix['include'][] = $job->toArray();
    }
}
