<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class PHPCS extends AbstractTool
{
    public function command(): string
    {
        return './vendor/ghostwriter/coding-standard/tools/phpcs -q --report=checkstyle | cs2pr';
    }

    /**
     * @return string[]
     */
    public function configuration(): array
    {
        return ['phpcs.xml.dist', 'phpcs.xml'];
    }
}
