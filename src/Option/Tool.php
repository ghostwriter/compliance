<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

use Ghostwriter\Compliance\Tool\Codeception;
use Ghostwriter\Compliance\Tool\ComposerRequireChecker;
use Ghostwriter\Compliance\Tool\EasyCodingStandard;
use Ghostwriter\Compliance\Tool\GrumPHP;
use Ghostwriter\Compliance\Tool\Infection;
use Ghostwriter\Compliance\Tool\MarkdownLint;
use Ghostwriter\Compliance\Tool\Phan;
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
    public const CODECEPTION = Codeception::class;

    /**
     * @var string
     */
    public const COMPOSER_REQUIRE_CHECKER = ComposerRequireChecker::class;

    /**
     * @var string
     */
    public const EASY_CODING_STANDARD = EasyCodingStandard::class;

    /**
     * @var string
     */
    public const GRUMPHP = GrumPHP::class;

    /**
     * @var string
     */
    public const INFECTION = Infection::class;

    /**
     * @var string
     */
    public const MARKDOWNLINT = MarkdownLint::class;

    /**
     * @var string
     */
    public const PHAN = Phan::class;

    /**
     * @var string
     */
    public const PHP_CS_FIXER = PHPCSFixer::class;

    /**
     * @var string
     */
    public const PHP_MESS_DETECTOR = 'PHP Mess Detector';

    /**
     * @var string
     */
    public const PHP_METRICS = 'PHP Metrics';

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
    public const PHPSTAN = 'PHPStan';

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
