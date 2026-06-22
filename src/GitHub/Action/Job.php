<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\GitHub\Action;

use Ghostwriter\Compliance\Enum\ComposerStrategy;
use Ghostwriter\Compliance\Enum\OperatingSystem;
use Ghostwriter\Compliance\Enum\PhpVersion;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;

use function file_exists;
use function implode;
use function sprintf;

final readonly class Job
{
    /** @param list<string> $extensions */
    public function __construct(
        private string $name,
        private string $command,
        private array $extensions,
        private string $cacheDirectory,
        private string $composerJsonPath,
        private string $composerLockPath,
        private ComposerStrategy $composerStrategy,
        private PhpVersion $phpVersion,
        private OperatingSystem $operatingSystem,
        private bool $experimental,
    ) {}

    /** @param list<string> $extensions */
    public static function new(
        string $name,
        string $command,
        array $extensions,
        string $composerCacheFilesDirectory,
        string $composerJsonPath,
        string $composerLockPath,
        PhpVersion $phpVersion,
        ComposerStrategy $composerStrategy = ComposerStrategy::LOCKED,
        OperatingSystem $operatingSystem = OperatingSystem::UBUNTU,
        bool $experimental = false,
    ): self {
        return new self(
            name: $name,
            command: $command,
            extensions: $extensions,
            cacheDirectory: $composerCacheFilesDirectory,
            composerJsonPath: $composerJsonPath,
            composerLockPath: $composerLockPath,
            composerStrategy: $composerStrategy,
            phpVersion: $phpVersion,
            operatingSystem: $operatingSystem,
            experimental: $experimental,
        );
    }

    /**
     * @return array{
     *     name:string,
     *     runCommand:string,
     *     installCommand:string,
     *     validateCommand:string,
     *     composerCacheFilesDirectory:string,
     *     extensions:list<string>,
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
            'extensions' => $this->extensions,
            'experimental' => $this->experimental,
            'cacheDirectory' => $this->cacheDirectory,
            'php' => $this->phpVersion->toString(),
            'os' => $this->operatingSystem->toString(),
            'dependency' => $this->composerStrategy->toString(),
            'command' => $this->command(),
        ];
    }

    /** @return string */
    private function command(): string
    {
        if (! file_exists($this->composerJsonPath)) {
            return 'echo "composer.json does not exist" && exit 0;';
        }

        return $this->wrap([
            '(composer validate --no-check-publish --no-check-lock --no-interaction --ansi --strict || true)',
            // 'composer config --global github-oauth.github.com ' . (getenv('GITHUB_TOKEN') ?: ''),
            '(composer config --no-plugins allow-plugins.ghostwriter/coding-standard false || true)',
            $this->composerInstallCommand(),
            // 'composer config --global --auth --unset github-oauth.github.com',
            $this->command,
        ]);
    }

    private function composerInstallCommand(): string
    {
        $composerOptions = ['--no-interaction', '--no-progress', '--ansi'];

        $composerCommand = match ($this->composerStrategy) {
            ComposerStrategy::LOCKED => 'install',
            default => 'update',
        };

        if (ComposerStrategy::LOWEST === $this->composerStrategy) {
            $composerOptions[] = '--prefer-lowest';
            $composerOptions[] = '--prefer-stable';
        }

        if (! file_exists($this->composerLockPath)) {
            $composerCommand = 'update';
        }

        return sprintf('composer %s %s', $composerCommand, implode(' ', $composerOptions));
    }

    /** @param string[] $commands */
    private function wrap(array $commands): string
    {
        return implode(' && ', $commands);
    }

    public static function noop(FilesystemInterface $filesystem): self
    {
        $currentDirectory = $filesystem->currentWorkingDirectory();

        $name = 'Noop';

        return new self(
            name: $name,
            command: sprintf('echo "%s"', $name),
            extensions: [],
            cacheDirectory: '/home/runner/.cache/composer/files',
            composerJsonPath: $currentDirectory . \DIRECTORY_SEPARATOR . 'composer.json',
            composerLockPath: $currentDirectory . \DIRECTORY_SEPARATOR . 'composer.lock',
            composerStrategy: ComposerStrategy::LOCKED,
            phpVersion: PhpVersion::latest(),
            operatingSystem: OperatingSystem::UBUNTU,
            experimental: true,
        );
    }
}
