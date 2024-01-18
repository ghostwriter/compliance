<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum ComposerDependency: string
{
    case HIGHEST = 'highest';
    case LOCKED = 'locked';
    case LOWEST = 'lowest';

    public function toString(): string
    {
        return $this->value;
    }

    public static function isExperimental(self $composerDependency): bool
    {
        return $composerDependency === self::LOWEST;
    }
}
