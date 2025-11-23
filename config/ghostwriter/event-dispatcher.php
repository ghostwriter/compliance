<?php

declare(strict_types=1);

use Ghostwriter\Compliance\EventDispatcher\Event\CheckEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\CopyWorkflowEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\GitHubEventInterface;
use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\EventDispatcher\Listener\CheckListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\CopyWorkflowListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\Debug;
use Ghostwriter\Compliance\EventDispatcher\Listener\GitHubListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\MatrixListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\OutputListener;

/**
 * @return array{
 *     'listen': array<'object'|class-string,list<class-string>>
 * }
 */
return [
    'listen' => [
        'object' => [
            //             Debug::class,
            //             Logger::class
        ],
        CheckEvent::class => [CheckListener::class],
        MatrixEvent::class => [MatrixListener::class],
        GitHubEventInterface::class => [GitHubListener::class],
        OutputEvent::class => [OutputListener::class],
        CopyWorkflowEvent::class => [Debug::class, CopyWorkflowListener::class],
    ],
];
