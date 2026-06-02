<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Container\Extension;

use Ghostwriter\Compliance\EventDispatcher\Event\CheckEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\CopyWorkflowEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\MatrixEvent;
use Ghostwriter\Compliance\EventDispatcher\Event\OutputEvent;
use Ghostwriter\Compliance\EventDispatcher\Listener\CheckListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\CopyWorkflowListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\Debug;
use Ghostwriter\Compliance\EventDispatcher\Listener\GitHubListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\Logger;
use Ghostwriter\Compliance\EventDispatcher\Listener\MatrixListener;
use Ghostwriter\Compliance\EventDispatcher\Listener\OutputListener;
use Ghostwriter\Compliance\Interface\EventDispatcher\Event\GitHubEventInterface;
use Ghostwriter\EventDispatcher\Container\AbstractListenerProviderExtension;
use Ghostwriter\EventDispatcher\Interface\ListenerProviderInterface;

/**
 * @see ListenerProviderExtensionTest
 *
 * @extends AbstractListenerProviderExtension<ListenerProviderInterface>
 */
final readonly class ListenerProviderExtension extends AbstractListenerProviderExtension
{
    /** @var array<'object'|class-string,list<class-string>> */
    public const array LISTENERS = [
        'object' => [Debug::class],
        CheckEvent::class => [CheckListener::class],
        MatrixEvent::class => [MatrixListener::class],
        GitHubEventInterface::class => [GitHubListener::class, Logger::class],
        OutputEvent::class => [OutputListener::class],
        CopyWorkflowEvent::class => [CopyWorkflowListener::class],
    ];
}
