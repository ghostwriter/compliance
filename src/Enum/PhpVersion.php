<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Enum;

enum PhpVersion: int
{
    case PHP_80 = 80000;
    case PHP_81 = 80100;
    case PHP_82 = 80200;
    case PHP_83 = 80300;
}
