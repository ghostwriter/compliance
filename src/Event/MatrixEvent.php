<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Service\Job;
use Ghostwriter\Json\Json;

final class MatrixEvent extends AbstractEvent
{
    /**
     * @var array{
     *     exclude:array<array-key,string>,
     *     include:array<array-key,array{
     *     name:string,
     *     command:string,
     *     extensions:array<string>,
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
        $matrix = $this->matrix['include'];
        if ($matrix === []) {
            $this->include(Job::noop());
        }

        return (new Json())->encode($this->matrix);
    }

    public function include(Job $job): void
    {
        $this->matrix['include'][] = $job->toArray();
    }
}
