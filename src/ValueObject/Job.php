<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ValueObject;

final class Job
{
    public function __construct(
        private string $name,
        private string $command,
        private array $dependencies,
        private int $php = PhpVersion::CURRENT_STABLE,
        private bool $experimental =false,
        private string $os = 'ubuntu-latest'
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'command' => $this->command,
            'os' => $this->os,
            'php' => PhpVersion::TO_STRING[$this->php],
            'dependencies'=>$this->dependencies,
            'experimental'=> $this->experimental,
        ];
    }
}
