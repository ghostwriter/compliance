#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Container\Container;
use function dirname;
use function realpath;

/** @var null|string $_composer_autoload_path */
(static function (string $composerAutoloadPath): void {
    /**
     * Find the path to 'vendor/autoload.php'.
     *
     * @psalm-suppress UnresolvableInclude
     */
    require realpath($composerAutoloadPath) ?: fwrite(
        STDERR,
        implode(PHP_EOL, [
            '',
            '[ERROR]Cannot locate "vendor/autoload.php"',
            'please run "composer install"',
            '',
        ]) . PHP_EOL
    ) && exit(1);

    /**
     * Here be dragons.
     */
    Compliance::main(Container::getInstance());
})($_composer_autoload_path ?? dirname(__DIR__) . '/vendor/autoload.php');
