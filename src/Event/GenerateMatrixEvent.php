<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\ValueObject\Job;
use Ghostwriter\Compliance\ValueObject\PhpVersion;
use function json_encode;

final class GenerateMatrixEvent extends AbstractEvent
{
    /**
     * @var array{
     *     dependencies:list<string>,
     *     exclude:list<string>,
     *     experimental:list<bool>,
     *     include:list<array{name:string,command:string}>,
     *     os:list<string>,
     *     php:list<string>,
     *     name:list<string>
     * }
     *     {
    "php":"7.4",
    "dependencies":"locked",
    "extensions":[],
    "ini":["memory_limit=-1"],
    "command":"./vendor/bin/phpcs"
    }
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
