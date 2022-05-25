<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Contract\PresenceInterface;
use Ghostwriter\Compliance\Event\GenerateMatrixEvent;
use Ghostwriter\Compliance\ValueObject\Tool;
use Ghostwriter\Container\Container;
use Throwable;

final class GenerateMatrixListener implements EventListenerInterface
{
    public function __construct(private Container $container)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(GenerateMatrixEvent $event): void
    {
        $input = $event->getInput();
        $output = $event->getOutput();

        foreach ($this->container->tagged(Tool::class) as $file) {
            /** @var PresenceInterface $tool */
            $tool = $this->container->get($file);
            if ($tool instanceof PresenceInterface) {
                if ($tool->isPresent()) {
                    $output->success($file);
                } else {
                    $output->warning($file);
                }
            } else {
                $output->error($file);
            }
        }
    }
}
