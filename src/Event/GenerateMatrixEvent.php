<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Event;

use Ghostwriter\Compliance\Contract\ToolInterface;
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
        'experimental' => [false],
        'dependencies' => ['latest', 'locked', 'lowest'],
        'php' => ['8.0', '8.1'],
        //        'job' => ['empty'],
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
        $result = json_encode($this->matrix);
        if (false === $result) {
            return '{}';
        }
        return $result;
    }

    public function include(ToolInterface $tool, int $phpVersion): void
    {
        $this->matrix['include'][] = [
            'name' => $tool->name(),
            'command' => $tool->command(),
            'php' => PhpVersion::TO_STRING[$phpVersion],
        ];
//        $this->matrix['name'][$tool::class] = $tool->name();
//        $this->matrix['command'][$tool::class] = $tool->command();
    }
}
