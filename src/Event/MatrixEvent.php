<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Option\Job;
use Ghostwriter\Json\Json;
use Throwable;
use const JSON_THROW_ON_ERROR;
use function json_encode;

final class MatrixEvent extends AbstractEvent
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
     * @throws Throwable
     */
    public function getMatrix(): string
    {
        return Json::encode($this->matrix);
    }

    public function include(Job $job): void
    {
        $this->matrix['include'][] = $job->toArray();
    }
}
