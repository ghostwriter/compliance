<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final class Job
{
    /**
     * @param array<string> $extensions
     */
    public function __construct(
        private readonly string $name,
        private readonly string $command,
        private readonly array $extensions,
        private readonly string $dependency,
        private readonly int $php = PhpVersion::CURRENT_STABLE,
        private readonly bool $experimental =false,
        private readonly string $os = 'ubuntu-latest'
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
