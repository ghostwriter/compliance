<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

final class Process
{
    public static function execute(array $command): array
    {
        if (!function_exists('proc_open')) {
            throw new \RuntimeException('proc_open is not available');
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
            throw new \RuntimeException('Failed to execute command: ' . implode(' ', $command));
        }

        fclose($pipes[0]);

        $stdout = (string) stream_get_contents($pipes[1]);
        $stderr = (string) stream_get_contents($pipes[2]);

        proc_close($process);

        return [$stdout, $stderr];
    }
}

