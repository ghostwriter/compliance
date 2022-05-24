<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Configuration\ComplianceConfiguration;
use Ghostwriter\Compliance\ValueObject\PhpVersion;
use Ghostwriter\Compliance\ValueObject\Tool;

return static function (ComplianceConfiguration $complianceConfig): void {
    $complianceConfig->phpVersion(PhpVersion::PHP_CURRENT_STABLE);

    $complianceConfig->skip([
        PhpVersion::PHP_82,
        Tool::PHP_CS_FIXER => [PhpVersion::PHP_81],
        Tool::COMPOSER_REQUIRE_CHECKER => [PhpVersion::PHP_81],
        Tool::CODECEPTION => [PhpVersion::PHP_80],
        __DIR__ . '*/tests/Fixture/*',
        __DIR__ . '*/vendor/*',
    ]);

    $complianceConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ]);
};
