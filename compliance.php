<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Configuration\ComplianceConfiguration;
use Ghostwriter\Compliance\Option\ComposerDependency;
use Ghostwriter\Compliance\Option\PhpVersion;
use Ghostwriter\Compliance\Tool\ComposerRequireChecker;
use Ghostwriter\Compliance\Tool\Infection;
use Ghostwriter\Compliance\Tool\PHPBench;
use Ghostwriter\Compliance\Tool\PHPCS;
use Ghostwriter\Compliance\Tool\PHPCSFixer;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\Tool\Psalm;

return static function (ComplianceConfiguration $complianceConfiguration): void {
    ($complianceConfiguration)([
        ComposerRequireChecker::class => [PhpVersion::STABLE => [ComposerDependency::LOCKED]],
        Infection::class => [PhpVersion::ANY => ComposerDependency::ALL],
        PHPBench::class => [PhpVersion::ANY => ComposerDependency::ALL],
        PHPCS::class => [PhpVersion::STABLE => [ComposerDependency::LOCKED]],
        PHPCSFixer::class => [PhpVersion::STABLE => [ComposerDependency::LOCKED]],
        PHPUnit::class => [PhpVersion::ANY => ComposerDependency::ALL],
        Psalm::class => [PhpVersion::LATEST => [ComposerDependency::LOCKED]],
    ],
    [
        PhpVersion::DEV,
        ComposerDependency::LOWEST,
        PHPUnit::class => [
            PhpVersion::PHP_82 => [
                ComposerDependency::HIGHEST,
                ComposerDependency::LOCKED,
                ComposerDependency::LOWEST,
            ]
        ],
        ComposerRequireChecker::class => [PhpVersion::STABLE => [ComposerDependency::LOCKED]],
        Infection::class => [PhpVersion::LATEST => [ComposerDependency::LOCKED]],
    ]);
};
