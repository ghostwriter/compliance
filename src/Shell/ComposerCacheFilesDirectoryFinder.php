<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Shell;

use Ghostwriter\Compliance\Exception\FailedToFindComposerCacheFilesDirectoryException;
use Ghostwriter\Shell\Interface\ShellInterface;
use Throwable;

use function mb_trim;

final readonly class ComposerCacheFilesDirectoryFinder
{
    public function __construct(
        private ShellInterface $shell,
        private ComposerExecutableFinder $composerExecutableFinder,
    ) {}

    /**
     * @throws Throwable
     * @throws FailedToFindComposerCacheFilesDirectoryException
     */
    public function __invoke(): string
    {
        $result = $this->shell->execute(
            ($this->composerExecutableFinder)(),
            ['--no-ansi', '--no-interaction', '--no-plugins', '--no-scripts', 'config', 'cache-files-dir'],
        );

        if ($result->exitCode() !== 0) {
            throw new FailedToFindComposerCacheFilesDirectoryException($result->stderr());
        }

        return mb_trim($result->stdout());
    }
}
