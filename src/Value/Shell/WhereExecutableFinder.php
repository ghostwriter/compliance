<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Value\Shell;

use Ghostwriter\Compliance\Exception\FailedToFindExecutableException;
use Ghostwriter\Shell\Interface\ShellInterface;

use const DIRECTORY_SEPARATOR;

use function mb_trim;
use function sprintf;

final readonly class WhereExecutableFinder
{
    public function __construct(
        private ShellInterface $shell,
    ) {}

    /** @throws FailedToFindExecutableException */
    public function __invoke(string $executable): string
    {
        /** @var ?non-empty-string $where */
        static $where;

        $where ??= (DIRECTORY_SEPARATOR === '/' ? 'which' : 'where.exe');

        $result = $this->shell->execute($where, [$executable]);

        $stdout = mb_trim($result->stdout());

        if ('' === $stdout || $result->exitCode() !== 0) {
            throw new FailedToFindExecutableException(
                sprintf('Failed to find executable "%s": %s', $executable, $result->stderr())
            );
        }

        return $stdout;
    }
}
