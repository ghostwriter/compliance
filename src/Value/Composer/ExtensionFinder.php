<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Composer;

use Throwable;

final readonly class ExtensionFinder
{
    public function __construct(
        private Composer $composer,
    ) {}

    /** @throws Throwable */
    public function find(string $composerJsonPath): Extensions
    {
        $extensions = [];

        $composerJson = $this->composer->readJsonFile($composerJsonPath);

        foreach ($composerJson->getRequire() as $requireDevList) {
            if (! $requireDevList instanceof Extension) {
                continue;
            }

            $extensions[] = $requireDevList;
        }

        foreach ($composerJson->getRequireDev() as $requireDevList) {
            if (! $requireDevList instanceof Extension) {
                continue;
            }

            $extensions[] = $requireDevList;
        }

        return new Extensions($extensions);
    }
}
