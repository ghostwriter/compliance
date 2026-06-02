<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer\Json;

use Ghostwriter\Compliance\Composer\Resolver\InstalledVersionsResolver;
use Ghostwriter\Filesystem\Interface\FilesystemInterface;
use Ghostwriter\Json\Interface\JsonInterface;
use InvalidArgumentException;
use Throwable;

final readonly class ComposerJsonReader
{
    public function __construct(
        private FilesystemInterface $filesystem,
        private JsonInterface $json,
        private InstalledVersionsResolver $installedVersionsResolver
    ) {}

    /** @throws Throwable */
    public function read(string $composerJsonPath): ComposerJson
    {
        if ($this->filesystem->missing($composerJsonPath)) {
            throw new InvalidArgumentException('Composer JSON file does not exist');
        }

        $composerJsonContents = $this->filesystem->read($composerJsonPath);

        return new ComposerJson(
            $composerJsonPath,
            $this->json->decode($composerJsonContents),
            $this->json,
            $this->installedVersionsResolver
        );
    }
}
