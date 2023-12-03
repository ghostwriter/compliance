<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use RuntimeException;
use function fclose;
use function function_exists;
use function implode;
use function proc_close;
use function stream_get_contents;
use function proc_open;

final class Process
{
    /**
     * @param list<string> $command
     * @return array{string,string}
     */
    public static function execute(array $command): array
    {
        if (!function_exists('proc_open')) {
            throw new RuntimeException('proc_open is not available');
        }

        $pipes = [];

        $process = proc_open(
            $command,
            [
                ['pipe', 'rb'],
                ['pipe', 'wb'], // stdout
                ['pipe', 'wb'], // stderr
            ],
            $pipes
        );

        if (false === $process)
        {
            throw new RuntimeException('Failed to execute command: ' . implode(' ', $command));
        }

        fclose($pipes[0]);

        $stdout = (string) stream_get_contents($pipes[1]);
        $stderr = (string) stream_get_contents($pipes[2]);

        proc_close($process);

        return [$stdout, $stderr];
    }
}

