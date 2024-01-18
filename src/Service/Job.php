<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use Ghostwriter\Compliance\Enum\ComposerDependency;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use function file_exists;
use function getcwd;
use function implode;
use function sprintf;

final readonly class Job
{
    /**
     * @param array<string> $extensions
     */
    public function __construct(
        private string $name,
        private string $command,
        private array $extensions,
        private string $composerCacheFilesDirectory,
        private string $composerJsonPath,
        private string $composerLockPath,
        private ComposerDependency $composerDependency,
        private PhpVersion $phpVersion,
        private OperatingSystem $operatingSystem,
        private bool $experimental,
    ) {
    }

    /**
     * @return array{
     *     name:string,
     *     runCommand:string,
     *     installCommand:string,
     *     validateCommand:string,
     *     composerCacheFilesDirectory:string,
     *     extensions:array<string>,
     *     os:string,
     *     php:string,
     *     dependency:string,
     *     experimental:bool
     * }
     */
    public function toArray(): array
    {
        $composerOptions = ['--no-interaction', '--no-progress', '--ansi'];

        switch ($this->composerDependency) {
            case ComposerDependency::HIGHEST:
                $composerCommand = 'update';
                break;
            case ComposerDependency::LOWEST:
                $composerCommand = 'update';
                $composerOptions[] = '--prefer-lowest';
                $composerOptions[] = '--prefer-stable';
                break;
            default:
                $composerCommand = 'install';
                break;
        }

        if (! file_exists($this->composerLockPath)) {
            $composerCommand = 'update';
        }

        $validateCommand = file_exists($this->composerJsonPath) ?
            'composer validate --no-check-publish --no-check-lock --no-interaction --ansi --strict' :
            'echo "composer.json does not exist" && exit 1;';

        return [
            'name' => $this->name,
            'runCommand' => $this->command,
            'composerCacheFilesDirectory' => $this->composerCacheFilesDirectory,
            'os' => $this->operatingSystem->toString(),
            'php' => $this->phpVersion->toString(),
            'dependency' => $this->composerDependency->toString(),
            'experimental' => $this->experimental,
            'extensions' => $this->extensions,
            'validateCommand' => $validateCommand,
            'installCommand' => sprintf('composer %s %s', $composerCommand, implode(' ', $composerOptions)),
        ];
    }

    public static function new(
        string $name,
        string $command,
        array $extensions,
        string $composerCacheFilesDirectory,
        string $composerJsonPath,
        string $composerLockPath,
        PhpVersion $phpVersion,
        ComposerDependency $composerDependency = ComposerDependency::LOCKED,
        OperatingSystem $operatingSystem = OperatingSystem::UBUNTU,
        bool $experimental = false,
    ): self {
        return new self(
            name: $name,
            command: $command,
            extensions: $extensions,
            composerCacheFilesDirectory: $composerCacheFilesDirectory,
            composerJsonPath: $composerJsonPath,
            composerLockPath: $composerLockPath,
            composerDependency: $composerDependency,
            phpVersion: $phpVersion,
            operatingSystem: $operatingSystem,
            experimental: $experimental,
        );
    }

    public static function noop(): self
    {
        $name = 'Noop';
        $currentDirectory = getcwd() ?: '.';
        return new self(
            name: $name,
            command: sprintf('echo "%s"', $name),
            extensions: [],
            composerCacheFilesDirectory: '/home/runner/.cache/composer/files',
            composerJsonPath: $currentDirectory,
            composerLockPath: $currentDirectory,
            composerDependency: ComposerDependency::LOCKED,
            phpVersion: PhpVersion::latest(),
            operatingSystem: OperatingSystem::UBUNTU,
        );
    }
}
