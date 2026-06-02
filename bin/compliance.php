<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use ErrorException;

use const DIRECTORY_SEPARATOR;
use const PHP_EOL;
use const STDERR;

use function dirname;
use function file_exists;
use function fwrite;
use function implode;
use function restore_error_handler;
use function set_error_handler;
use function sprintf;

/** @var ?string $_composer_autoload_path */
$_composer_autoload_path ??= implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']);

(static function (string $autoloadPath): void {
    if (! file_exists($autoloadPath)) {
        fwrite(STDERR, sprintf(implode(PHP_EOL, [
            '[ERROR]Failed to locate "%s"',
            'Please run "composer install"',
        ]), $autoloadPath));

        exit(1);
    }

    set_error_handler(
        static fn (
            int $severity,
            string $message,
            string $file,
            int $line
        ): never => throw new ErrorException($message, 0, $severity, $file, $line)
    );

    require $autoloadPath;

    /** #BlackLivesMatter */
    $exitCode = Compliance::new()->run($_SERVER['argv'] ?? []);

    restore_error_handler();

    exit($exitCode);
})($_composer_autoload_path);
