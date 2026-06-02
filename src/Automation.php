<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Compliance\Enum\Tool;

use function array_filter;
use function array_merge;

final readonly class Automation
{
    public function __construct(
        private array $composerStrategies = [],
        private array $operatingSystems = [],
        private array $phpVersions = [],
        private array $tools = [],
    ) {}

    public static function new(
        array $composerStrategies = [],
        array $operatingSystems = [],
        array $phpVersions = [],
        array $tools = [],
    ): self {
        return new self($composerStrategies, $operatingSystems, $phpVersions, $tools);
    }

    public function composerStrategies(ComposerStrategy ...$composerStrategy): self
    {
        return new self(
            array_merge($this->composerStrategies, $composerStrategy),
            $this->operatingSystems,
            $this->phpVersions,
            $this->tools,
        );
    }

    public function operatingSystems(OperatingSystem ...$operatingSystem): self
    {
        return new self(
            $this->composerStrategies,
            array_merge($this->operatingSystems, $operatingSystem),
            $this->phpVersions,
            $this->tools,
        );
    }

    public function phpVersions(PhpVersion ...$phpVersion): self
    {
        return new self(
            $this->composerStrategies,
            $this->operatingSystems,
            array_merge($this->phpVersions, $phpVersion),
            $this->tools,
        );
    }

    public function skip(ComposerStrategy|OperatingSystem|PhpVersion|Tool ...$exclusions): self
    {
        $instance = $this;

        foreach ($exclusions as $exclusion) {
            $instance = match (true) {
                $exclusion instanceof ComposerStrategy => new self(
                    array_filter(
                        $instance->composerStrategies,
                        static fn (ComposerStrategy $composerStrategy): bool => $composerStrategy !== $exclusion,
                    ),
                    $instance->operatingSystems,
                    $instance->phpVersions,
                    $instance->tools,
                ),
                $exclusion instanceof OperatingSystem => new self(
                    $instance->composerStrategies,
                    array_filter(
                        $instance->operatingSystems,
                        static fn (OperatingSystem $operatingSystem): bool => $operatingSystem !== $exclusion,
                    ),
                    $instance->phpVersions,
                    $instance->tools,
                ),
                $exclusion instanceof PhpVersion => new self(
                    $instance->composerStrategies,
                    $instance->operatingSystems,
                    array_filter(
                        $instance->phpVersions,
                        static fn (PhpVersion $phpVersion): bool => $phpVersion !== $exclusion,
                    ),
                    $instance->tools,
                ),
                default => new self(
                    $instance->composerStrategies,
                    $instance->operatingSystems,
                    $instance->phpVersions,
                    array_filter($instance->tools, static fn (Tool $tool): bool => $tool !== $exclusion),
                ),
            };
        }

        return $instance;
    }

    /** @return list<ComposerStrategy|OperatingSystem|PhpVersion|Tool> */
    public function toArray(): array
    {
        return [...$this->composerStrategies, ...$this->operatingSystems, ...$this->phpVersions, ...$this->tools];
    }

    public function tools(Tool ...$tool): self
    {
        return new self(
            $this->composerStrategies,
            $this->operatingSystems,
            $this->phpVersions,
            array_merge($this->tools, $tool),
        );
    }
}
