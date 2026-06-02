<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer;

final readonly class ComposerFileField
{
    /** @var string */
    public const string DESCRIPTION = 'description';

    /** @var string */
    public const string LICENSE = 'license';

    /** @var string */
    public const string NAME = 'name';

    /** @var string */
    public const string REQUIRE = 'require';

    /** @var string */
    public const string VERSION = 'version';
}
