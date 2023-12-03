<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Option\Job;
use Ghostwriter\Json\Json;
use Throwable;

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

    /**
     * @param array $matrices
     */
    public function exclude(array $matrices): void
    {
        /** @var string $matrix */
        foreach ($matrices as $matrix) {
            $this->matrix['exclude'][] = $matrix;
        }
    }

    /**
     * @throws Throwable
     */
    public function getMatrix(): string
    {
        $matrix = $this->matrix['include'];
        if ($matrix === []) {
            $tempPath = sys_get_temp_dir();
            $this->include(new Job('No tests', 'echo "No tests"', [], $tempPath, $tempPath, $tempPath, 'locked'));
        }

        return Json::encode($this->matrix);
    }

    public function include(Job $job): void
    {
        $this->matrix['include'][] = $job->toArray();
    }
}
