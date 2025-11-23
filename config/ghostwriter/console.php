<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Console\Command\CheckCommand;
use Ghostwriter\Compliance\Console\Command\MatrixCommand;
use Ghostwriter\Compliance\Console\Command\RunCommand;
use Ghostwriter\Compliance\Console\Command\WorkflowCommand;
use Symfony\Component\Console\Command\Command;

/**
 * @return array{
 *     name: string,
 *     package: string,
 *     auto_exit: bool,
 *     single_command: bool,
 *     default_command: string,
 *     catch_errors: bool,
 *     catch_exceptions: bool,
 *     commands: class-string<Command>
 * }
 */
return [
    'name' => 'Compliance',
    'package' => 'ghostwriter/compliance',
    'auto_exit'       => false,
    'single_command'       => false,
    'default_command'  => 'run',
    'catch_errors'     => true,
    'catch_exceptions' => true,
    'commands' => [CheckCommand::class, MatrixCommand::class, RunCommand::class, WorkflowCommand::class],
];
