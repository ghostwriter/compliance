<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Ghostwriter\Compliance\Service\Composer;
use Ghostwriter\Compliance\Service\Composer\Extension;

final readonly class ExtensionFinder
{
    public function __construct(
        private Composer $composer,
    ) {
    }

    public function find(
        string $composerJsonPath
    ): Extensions
    {
        $extensions = [];

        $composerJson = $this->composer->readJsonFile($composerJsonPath);

        foreach ($composerJson->getRequire() as $dependency) {

            if (! $dependency instanceof Extension) {
                continue;
            }

            $extensions[] = $dependency;
        }

        foreach ($composerJson->getRequireDev() as $dependency) {
            if (! $dependency instanceof Extension) {
                continue;
            }

            $extensions[] = $dependency;
        }

        return new Extensions($extensions);
    }
}
