<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final readonly class Job
{
    /**
     * @param array<string> $extensions
     */
    public function __construct(
        private string $name,
        private string $command,
        private array  $extensions,
        private string $dependency,
        private int    $php = PhpVersion::STABLE,
        private bool   $experimental = false,
        private string $os = 'ubuntu-latest'
    ) {
    }

    /**
     * @return array{
     *     name:string,
     *     command:string,
     *     extensions:array<string>,
     *     os:string,
     *     php:string,
     *     dependency:string,
     *     experimental:bool
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'command' => $this->command,
            'os' => $this->os,
            'php' => PhpVersion::TO_STRING[$this->php],
            'dependency' => $this->dependency,
            'experimental' => $this->experimental,
            'extensions' => $this->extensions,
        ];
    }
}
