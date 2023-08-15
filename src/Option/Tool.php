<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Tool\ComposerRequireChecker;
use Ghostwriter\Compliance\Tool\ECS;
use Ghostwriter\Compliance\Tool\PHPBench;
use Ghostwriter\Compliance\Tool\PHPCS;
use Ghostwriter\Compliance\Tool\PHPCSFixer;
use Ghostwriter\Compliance\Tool\PHPUnit;
use Ghostwriter\Compliance\Tool\Psalm;
use Ghostwriter\Compliance\Tool\Rector;

final class Tool
{
    /**
     * @var string
     */
    public const COMPOSER_REQUIRE_CHECKER = ComposerRequireChecker::class;

    /**
     * @var string
     */
    public const EASY_CODING_STANDARD = ECS::class;

    /**
     * @var string
     */
    public const PHP_CS_FIXER = PHPCSFixer::class;

    /**
     * @var string
     */
    public const PHPBENCH = PHPBench::class;

    /**
     * @var string
     */
    public const PHPCS = PHPCS::class;

    /**
     * @var string
     */
    public const PHPUNIT = PHPUnit::class;

    /**
     * @var string
     */
    public const PSALM = Psalm::class;

    /**
     * @var string
     */
    public const RECTOR = Rector::class;
}
