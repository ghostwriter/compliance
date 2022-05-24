<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ValueObject;

final class Tool
{
    /**
     * @var string
     */
    public const CODECEPTION = 'Codeception';

    /**
     * @var string
     */
    public const COMPOSER_REQUIRE_CHECKER = 'Composer Require Checker';

    /**
     * @var string
     */
    public const GRUMPHP = 'GrumPHP';

    /**
     * @var string
     */
    public const PHAN = 'Phan';

    /**
     * @var string
     */
    public const PHP_CODE_SNIFFER = 'PHP Code Sniffer';

    /**
     * @var string
     */
    public const PHP_CS_FIXER = 'PHP CS Fixer';

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
    public const PHPSTAN = 'PHPStan';
}
