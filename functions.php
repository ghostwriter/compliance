<?php

declare(strict_types=1);

use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Event\OutputEvent;
use Ghostwriter\Compliance\Service\GithubActionOutput;
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
function dispatchOutputEvent(string|array $message): OutputEvent
{
   return dispatch(new OutputEvent(
       [
           '::echo::on',
           sprintf('::group::%s %s', Compliance::NAME, Compliance::BLACK_LIVES_MATTER),
           $message,
           '::endgroup::',
           '::echo::off'
       ]
   ));
}
function githubActionOutput(): GithubActionOutput
{
   return container()
       ->get(GithubActionOutput::class);
}
function debug(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
{
    githubActionOutput()
        ->debug($message, $file, $line, $col);
}
function warning(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
{
    githubActionOutput()
        ->warning($message, $file, $line, $col);
}
function error(string $message, ?string $file = null, ?int $line = null, ?int $col = null): void
{
    githubActionOutput()
        ->error($message, $file, $line, $col);
}
