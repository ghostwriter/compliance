<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\ValueObject\Job;
use JsonException;
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

    /**
     * @throws JsonException
     */
    public function getMatrix(): string
    {
        return json_encode($this->matrix, JSON_THROW_ON_ERROR);
    }

    public function include(Job $job): void
    {
        $this->matrix['include'][] = $job->toArray();
    }
}
