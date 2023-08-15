<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final readonly class Job
{
    /**
     * @param array<string> $extensions
     */
    public function __construct(
        private readonly string $name,
        private readonly string $command,
        private readonly array $extensions,
        private readonly string $dependency,
        private readonly int $php = PhpVersion::STABLE,
        private readonly bool $experimental = false,
        private readonly string $os = 'ubuntu-latest'
    ) {
        $this->experimental = $experimental || $php === PhpVersion::DEV;
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
