<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum PhpVersion: int
{
    case PHP_72 = 70200;
    case PHP_73 = 70300;
    case PHP_74 = 70400;
    case PHP_80 = 80000;
    case PHP_81 = 80100;
    case PHP_82 = 80200;
    case PHP_83 = 80300;
    case PHP_84 = 80400;

    public function toString(): string
    {
        return match ($this) {
            self::PHP_72 => '7.2',
            self::PHP_73 => '7.3',
            self::PHP_74 => '7.4',
            self::PHP_80 => '8.0',
            self::PHP_81 => '8.1',
            self::PHP_82 => '8.2',
            self::PHP_83 => '8.3',
            self::PHP_84 => '8.4',
        };
    }

    public static function experimental(): self
    {
        return self::PHP_84;
    }

    public static function isExperimental(self $phpVersion): bool
    {
        return $phpVersion->value >= self::experimental()->value;
    }

    public static function latest(): self
    {
        return self::PHP_83;
    }
}
