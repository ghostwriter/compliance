<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer\Lock;

use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Json\Interface\JsonInterface;
use InvalidArgumentException;
use Throwable;

final readonly class ComposerLockReader
{
    public function __construct(
        private FilesystemInterface $filesystem,
        private JsonInterface $json
    ) {}

    /** @throws Throwable */
    public function read(string $composerJsonPath): ComposerLock
    {
        if ($this->filesystem->missing($composerJsonPath)) {
            throw new InvalidArgumentException('Composer JSON file does not exist');
        }

        $composerJsonContents = $this->filesystem->read($composerJsonPath);

        return new ComposerLock($composerJsonPath, $this->json->decode($composerJsonContents));
    }
}
