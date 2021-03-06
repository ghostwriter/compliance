<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final class Job
{
    public function __construct(
        private string $name,
        private string $command,
        private string $dependency,
        private int $php = PhpVersion::CURRENT_STABLE,
        private bool $experimental =false,
        private string $os = 'ubuntu-latest'
    ) {
    }

    /**
     * @return array{
     *     name:string,
     *     command:string,
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
        ];
    }
}
