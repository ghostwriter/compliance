<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Configuration\ComplianceConfiguration;
use Ghostwriter\Compliance\Configuration\ValueObject\ComplianceConfigurationOption;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\ValueObject\PhpVersion;
use Ghostwriter\Compliance\ValueObject\Tool;

return static function (ComplianceConfiguration $complianceConfig): void {
    $complianceConfig->phpVersion(PhpVersion::PHP_CURRENT_STABLE);

    $complianceConfig->skip([
        PHPUnit::class => [],
        PhpVersion::PHP_52 => [],
        Tool::CODECEPTION => [PhpVersion::PHP_80],
        __DIR__ . '*/tests/Fixture/*',
        __DIR__ . '*/vendor/*',
        ComplianceConfigurationOption::class,
    ]);

    $complianceConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ]);
};
