<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\Exception\VariableNotFoundException;
use function array_key_exists;
use function getenv;

final readonly class EnvironmentVariables
{
    private function __construct(
        private array $variables = [],
    ) {
    }

    public function get(string $name, null|string $default = null): string
    {
        return match (true) {
            array_key_exists($name, $this->variables) => $this->variables[$name],
            $default !== null => $default,
            default => throw new VariableNotFoundException($name),
        };
    }

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->variables);
    }

    public static function new(): self
    {
        $variables = [];

        foreach ($_ENV as $name => $value) {
            $variables[$name] = (string) $value;
        }

        foreach (getenv() ?: [] as $name => $value) {
            $variables[$name] = (string) $value;
        }

        return new self($variables);
    }
}
