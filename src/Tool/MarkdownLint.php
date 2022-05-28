<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Tool;

final class MarkdownLint extends AbstractTool
{
    public function command(): string
    {
//        'markdownlint doc/book/**/*.md'
//        'markdownlint docs/book/**/*.md'
        return 'yamllint -d relaxed --no-warnings mkdocs.yml';
    }

    public function configuration(): array
    {
        return ['mkdocs.yml'];
    }
}
