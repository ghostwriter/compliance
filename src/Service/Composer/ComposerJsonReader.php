<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Ghostwriter\Json\Json;
use Ghostwriter\Compliance\Service\Composer\ComposerJson;

final readonly class ComposerJsonReader 
{
    public function read(
        string $composerJsonPath
    ): ComposerJson
    {
        if (!file_exists($composerJsonPath)) {
            throw new \InvalidArgumentException(
                'Composer JSON file does not exist: ' . $composerJsonPath
            );
        }

        $composerJsonContents = file_get_contents($composerJsonPath);

        if ($composerJsonContents === false){
            throw new \InvalidArgumentException('Composer JSON file could not be read');
        }

        return new ComposerJson($composerJsonPath, Json::decode($composerJsonContents));
    }
}