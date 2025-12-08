<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use ErrorException;
use Ghostwriter\Container\Container;
use Symfony\Component\Console\Application;
use Throwable;

use const E_ALL;
use const E_DEPRECATED;
use const E_NOTICE;
use const E_USER_DEPRECATED;
use const PHP_EOL;
use const STDERR;

use function dirname;
use function error_reporting;
use function file_exists;
use function fwrite;
use function implode;
use function restore_error_handler;
use function set_error_handler;
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

    set_error_handler(
        // Convert PHP errors to exceptions,
        static function (int $severity, string $message, string $file, int $line): void {
            if (0 === (error_reporting() & $severity)) {
                // Error not in mask
                return;
            }

            throw new ErrorException($message, 0, $severity, $file, $line);
        },
        // reports all errors except E_USER_DEPRECATED, E_DEPRECATED, E_STRICT, and E_NOTICE
        E_ALL & ~E_USER_DEPRECATED & ~E_DEPRECATED & ~E_NOTICE
    );

    require_once $composerAutoloadPath;

    restore_error_handler();

    /** #BlackLivesMatter */
    exit(Container::getInstance()->get(Application::class)->run());
})($_composer_autoload_path ?? implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']));
