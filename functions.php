<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\EventDispatcher\Interface\DispatcherInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;

function container(): ContainerInterface
{
    return Container::getInstance();
}
function dispatch(EventInterface $event): EventInterface
{
    return container()
        ->get(DispatcherInterface::class)
        ->dispatch($event);
}
function dispatchOutputEvent(string ...$message): OutputEvent
{
   return dispatch(new OutputEvent(...$message));
}
