<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service;

use Ghostwriter\Compliance\Exception\FailedToClosePipeException;
use Ghostwriter\Compliance\Exception\FailedToExecuteCommandException;
use Ghostwriter\Compliance\Exception\FailedToWriteToStdinException;
use Ghostwriter\Compliance\Exception\ProcOpenFunctionDoesNotExistException;
use function fclose;
use function function_exists;
use function fwrite;
use function implode;
use function proc_close;
use function proc_open;
use function stream_get_contents;

final readonly class Process
{
    /**
     * @param list<string> $command
     *
     * @return array{int,string,string}
     */
    public static function execute(
        array $command,
        ?string $currentWorkingDirectory = null,
        ?array $environmentVariables = null,
        ?string $input = null,
    ): array {
        if (! function_exists('proc_open')) {
            throw new ProcOpenFunctionDoesNotExistException();
        }

        $pipes = [];

        $descriptor_spec = [
            0 => ['pipe', 'rb'], // STDIN
            1 => ['pipe', 'wb'], // STDOUT
            2 => ['pipe', 'wb'], // STDERR
        ];

        $process = proc_open($command, $descriptor_spec, $pipes, $currentWorkingDirectory, $environmentVariables);

        if ($process === false) {
            throw new FailedToExecuteCommandException(implode(' ', $command));
        }

        /** @var array{0:resource,1:resource,2:resource} $pipes */
        if ($input !== null) {
            $bytesWritten = fwrite($pipes[0], $input);

            if ($bytesWritten === false) {
                throw new FailedToWriteToStdinException();
            }
        }

        $result = [stream_get_contents($pipes[1]), stream_get_contents($pipes[2])];

        foreach ($pipes as $pipe) {
            if (! fclose($pipe)) {
                throw new FailedToClosePipeException();
            }
        }

        return [proc_close($process), ...$result];
    }
}
