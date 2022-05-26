<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Contract;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

interface PresenceInterface
{
    public function __construct(Finder $finder, SymfonyStyle $output);

    public function isPresent(): bool;
}
