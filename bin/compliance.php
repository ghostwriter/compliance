#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Container\Container;
use Throwable;
use const STDERR;
use function dirname;
use function fwrite;
use function sprintf;

/** @var ?string $_composer_autoload_path */
(static function (string $composerAutoloadPath): void {
    if (! file_exists($composerAutoloadPath)) {
        fwrite(
            STDERR,
            sprintf('[ERROR]Failed to locate "%s"\n please run "composer install"\n', $composerAutoloadPath)
        );

        exit(1);
    }

    require $composerAutoloadPath;

    /**
     * #BlackLivesMatter.
     */
    try {
        Compliance::main();
    } catch (Throwable $throwable) {
        fwrite(STDERR, sprintf(
            '[%s] %s',
            $throwable::class,
            $throwable->getMessage(),
        ));

        exit(1);
    }
})($_composer_autoload_path ?? dirname(__DIR__) . '/vendor/autoload.php');
