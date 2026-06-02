<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

use const PHP_MAJOR_VERSION;
use const PHP_MINOR_VERSION;

use function array_filter;

enum PhpVersion: int
{
    case PHP_54 = 50400;
    case PHP_70 = 70000;
    case PHP_71 = 70100;
    case PHP_72 = 70200;
    case PHP_73 = 70300;
    case PHP_74 = 70400;
    case PHP_80 = 80000;
    case PHP_81 = 80100;
    case PHP_82 = 80200;
    case PHP_83 = 80300;
    case PHP_84 = 80400;
    case PHP_85 = 80500;
    case PHP_86 = 80600;

    // case PHP_90 = 90000;

    public function toString(): string
    {
        return match ($this) {
            self::PHP_54 => '5.4',
            self::PHP_70 => '7.0',
            self::PHP_71 => '7.1',
            self::PHP_72 => '7.2',
            self::PHP_73 => '7.3',
            self::PHP_74 => '7.4',
            self::PHP_80 => '8.0',
            self::PHP_81 => '8.1',
            self::PHP_82 => '8.2',
            self::PHP_83 => '8.3',
            self::PHP_84 => '8.4',
            self::PHP_85 => '8.5',
            self::PHP_86 => '8.6',
            // self::PHP_90 => '9.0',
        };
    }

    public static function current(): self
    {
        return self::from(PHP_MAJOR_VERSION * 10000 + PHP_MINOR_VERSION * 100);
    }

    public static function highest(): self
    {
        return self::PHP_86;
    }

    public static function isExperimental(self $phpVersion): bool
    {
        return match ($phpVersion) {
            self::PHP_86 => true,
            default => false
        };
    }

    public static function latest(): self
    {
        return self::PHP_85;
    }

    public static function lowest(): self
    {
        return self::PHP_54;
    }

    /** @return PhpVersion[] */
    public static function supported(): array
    {
        //        $currentValue = self::current()->value;
        //        $highestValue = self::highest()->value;
        //        $lowestValue = self::lowest()->value;
        //
        //        return array_filter(
        //            self::cases(),
        //            static fn(self $phpVersion): bool => match (true) {
        //                $lowestValue <= $phpVersion->value => $highestValue >= $phpVersion->value,
        //                default => false,
        //            }
        //        );
        return array_filter(
            self::cases(),
            static fn (self $phpVersion): bool
            => (self::lowest()->value <= $phpVersion->value && self::highest()->value >= $phpVersion->value)
            && self::current()->value >= $phpVersion->value
        );
    }
}
