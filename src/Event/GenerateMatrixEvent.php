<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use const JSON_UNESCAPED_SLASHES;
use function json_encode;

final class GenerateMatrixEvent extends AbstractEvent
{
    /**
     * @var array{
     *     dependencies:list<string>,
     *     exclude:list<string>,
     *     experimental:bool,
     *     include:list<string>,
     *     os:list<string>,
     *     php:list<string>,
     *     name:list<string>,
     *     name:string
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
        'include' => [
            [
                'name' => 'PHPUnit',
                'php' => '8.0',
                'command' => './vendor/bin/phpunit',
            ],
            //            [
            //                'name' => 'PHPCS',
            //                'php' => '8.0',
            //                'command' => './vendor/bin/phpcs',
            //            ],
        ],
        'exclude' => [],
        'experimental' => [false],
        'dependencies' => ['latest', 'locked', 'lowest'],
        'job' => ['empty'],
        'os' => ['ubuntu-latest'],
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

    public function setMatrix(array $matrices): void
    {
//        /** @var string $matrix */
//        foreach ($matrices as $matrix) {
//            $this->matrix['job'][] = $matrix;
//        }
    }
}
