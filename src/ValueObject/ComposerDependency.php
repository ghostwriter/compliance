<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ValueObject;

final class ComposerDependency
{
    /**
     * @var string
     */
    public const CONFIG = 'compliance:configuration:composer:dependency';

    /**
     * Equivalent to running "composer update".
     *
     * @var string
     */
    public const HIGHEST = 'highest';

    /**
     * Equivalent to running "composer install".
     *
     * @var string
     */
    public const LOCKED = 'locked';

    /**
     * Equivalent to running "composer update --prefer-lowest --prefer-stable".
     *
     * @var string
     */
    public const LOWEST = 'lowest';

    /**
     * Supported options.
     *
     * @var string
     */
    public const OPTIONS = [
        self::HIGHEST=>0,
        self::LOCKED=>0,
        self::LOWEST=>0,
    ];
}
