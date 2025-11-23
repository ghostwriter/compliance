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
        $self = $this;

        foreach ($exclusions as $exclusion) {
            $self = match (true) {
                $exclusion instanceof ComposerStrategy => new self(
                    array_filter(
                        $self->composerStrategies,
                        static fn (ComposerStrategy $composerStrategy): bool => $composerStrategy !== $exclusion,
                    ),
                    $self->operatingSystems,
                    $self->phpVersions,
                    $self->tools,
                ),
                $exclusion instanceof OperatingSystem => new self(
                    $self->composerStrategies,
                    array_filter(
                        $self->operatingSystems,
                        static fn (OperatingSystem $operatingSystem): bool => $operatingSystem !== $exclusion,
                    ),
                    $self->phpVersions,
                    $self->tools,
                ),
                $exclusion instanceof PhpVersion => new self(
                    $self->composerStrategies,
                    $self->operatingSystems,
                    array_filter(
                        $self->phpVersions,
                        static fn (PhpVersion $phpVersion): bool => $phpVersion !== $exclusion,
                    ),
                    $self->tools,
                ),
                default => new self(
                    $self->composerStrategies,
                    $self->operatingSystems,
                    $self->phpVersions,
                    array_filter($self->tools, static fn (Tool $tool): bool => $tool !== $exclusion),
                ),
            };
        }

        return $self;
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
