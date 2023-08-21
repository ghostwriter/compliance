<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Option;

final class ComposerDependency
{
    /**
     * Supported options.
     *
     * @var list<string>
     */
    public const SUPPORTED = [
        self::HIGHEST,
        self::LOCKED,
        self::LOWEST,
    ];

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
     * @var array<string, bool>
     */
    public const OPTIONS = [
        self::HIGHEST=>true,
        self::LOCKED=>true,
        self::LOWEST=>true,
    ];
}
