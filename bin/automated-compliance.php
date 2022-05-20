#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Ghostwriter\AutomatedCompliance;

use Ghostwriter\AutomatedCompliance\ServiceProvider\ApplicationServiceProvider;
use Ghostwriter\Container\Container;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use Symfony\Component\Console\Application;
use Throwable;

(static function (): void {
    define('PACKAGE', 'ghostwriter/automated-compliance');
    define('APP_NAME', 'Automated Compliance');
    define('MIN_PHP_VERSION', '8.0.0');

    foreach (['json', 'mbstring'] as $extension) {
        if (extension_loaded($extension)) {
            continue;
        }

        fwrite(STDERR, sprintf('%s requires the "%s" extension.' . PHP_EOL, APP_NAME, $extension));

        die(1);
    }

    if (! ini_get('date.timezone')) {
        ini_set('date.timezone', 'UTC');
    }

    /**
     * Find the path to 'vendor/autoload.php'.
     */
    require realpath(dirname(__DIR__, 3) . '/vendor/autoload.php')
        ?: realpath(dirname(__DIR__) . '/vendor/autoload.php')
            ?: realpath('vendor/autoload.php')
                ?: fwrite(
                    STDERR,
                    implode(PHP_EOL, [
                        '',
                        '[ERROR]Cannot locate "vendor/autoload.php"',
                        'please run "composer install"',
                        '',
                    ]) . PHP_EOL
                ) && die(1);

    // here be dragons

    $container = Container::getInstance();
    $container->build(ApplicationServiceProvider::class);
    try {
        $application = $container->build(Application::class, [
            'name' => $container->get('app.name'),
            'version' => $container->get('app.version'),
        ]);
        $application->run();
    } catch (Throwable $throwable) {
        $container->get(DispatcherInterface::class)->dispatch($throwable);

        throw $throwable;
    }
})();
