<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use const JSON_UNESCAPED_SLASHES;
use function json_encode;

final class GenerateMatrixEvent extends AbstractEvent
{
    /**
     * @var array{
     *     exclude:list<string>,
     *     experimental:bool,
     *     include:list<string>,
     *     os:list<string>,
     *     php:list<string>,
     *     name:list<string>,
     *     name:string
     * }
     */
    private array $matrix = [
        'include' => [],
        'exclude' => [],
        'name' => ['Step name'],
        'experimental' => [false],
        'php' => ['8.0','8.1'],
        'job' => ['Job Title'],
        'os' => ['macos-latest', 'ubuntu-latest', 'windows-latest'],
    ];

    public function getMatrix(): string
    {
        $result = json_encode($this->matrix, JSON_UNESCAPED_SLASHES);
        if (false === $result) {
            return '{}';
        }
        return $result;
    }

    public function include(array $matrices): void
    {
        /** @var string $matrix */
        foreach ($matrices as $matrix) {
            $this->matrix['include'][] = $matrix;
        }
    }

    public function exclude(array $matrices): void
    {
        /** @var string $matrix */
        foreach ($matrices as $matrix) {
            $this->matrix['exclude'][] = $matrix;
        }
    }

    public function setMatrix(array $matrices): void
    {
        /** @var string $matrix */
        foreach ($matrices as $matrix) {
            $this->matrix['job'][] = $matrix;
        }
    }
}
