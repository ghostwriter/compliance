<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Interface\EventListenerInterface;
use Ghostwriter\EventDispatcher\Interface\EventInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function mb_strrpos;
use function mb_substr;
use function sprintf;

final readonly class Debug implements EventListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle
    ) {
    }

    /**
     * @param EventInterface<bool> $event
     */
    public function __invoke(EventInterface $event): void
    {
        $eventName = mb_substr($event::class, mb_strrpos($event::class, '\\') + 1);

        $this->symfonyStyle->title(sprintf(
            '<fg=white;bg=black;options=bold>DEBUG START:</> <info>%s</info>',
            $eventName
        ));

        $this->symfonyStyle->table(['name', 'class'], [[$eventName, $event::class]]);

        $this->symfonyStyle->title(sprintf(
            '<fg=white;bg=black;options=bold>DEBUG END:  </> <info>%s</info>',
            $eventName
        ));
    }
}
