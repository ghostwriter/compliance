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
        private string $composerCacheFilesDirectory,
        private string $composerJsonPath,
        private string $composerLockPath,
        private string $dependency,
        private int    $php = PhpVersion::LATEST,
        private bool   $experimental = false,
        private string $os = 'ubuntu-latest',
    ) {
    }

    public static function noop(
        string $name
    ): self {
        $currentDirectory = getcwd() ?: '.';
        return new self(
            name: $name,
            command: sprintf('echo "%s"', $name),
            extensions: [],
            composerCacheFilesDirectory: '~/.cache/composer/files',
            composerJsonPath: $currentDirectory,
            composerLockPath: $currentDirectory,
            dependency: 'locked'
        );
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
        $composerDependency = $this->dependency;
        $composerOptions = ['--no-interaction', '--no-progress', '--ansi'];
        switch ($composerDependency) {
            case 'highest':
                $composerCommand = 'update';
                break;
            case 'lowest':
                $composerCommand = 'update';
                $composerOptions[] = '--prefer-lowest';
                $composerOptions[] = '--prefer-stable';
                break;
            default:
                $composerCommand = 'install';
                break;
        };

        if (!file_exists($this->composerLockPath)) {
            $composerCommand = 'update';
        }

        $validateCommand = file_exists($this->composerJsonPath) ?
            'composer validate --no-check-publish --no-check-lock --no-interaction --ansi --strict' :
            'echo "composer.json does not exist" && exit 1;';

        return [
            'name' => $this->name,
            'runCommand' => $this->command,
            'composerCacheFilesDirectory' => $this->composerCacheFilesDirectory,
            'os' => $this->os,
            'php' => PhpVersion::TO_STRING[$this->php],
            'dependency' => $this->dependency,
            'experimental' => $this->experimental,
            'extensions' => $this->extensions,
            'validateCommand' => $validateCommand,
            'installCommand' => sprintf('composer %s %s', $composerCommand, implode(' ', $composerOptions))
        ];
    }
}
