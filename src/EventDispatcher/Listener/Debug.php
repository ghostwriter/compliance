<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\EventDispatcher\Listener;

use Ghostwriter\Compliance\EnvironmentVariables;
use Ghostwriter\Compliance\Interface\EventDispatcher\ListenerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function mb_strrpos;
use function mb_substr;
use function sprintf;

final readonly class Debug implements ListenerInterface
{
    public function __construct(
        private SymfonyStyle $symfonyStyle,
        private EnvironmentVariables $environmentVariables
    ) {}

    /** @throws Throwable */
    public function __invoke(object $event): void
    {
        if (
            $this->environmentVariables->get('GITHUB_DEBUG', '0') !== '1'
            || $this->environmentVariables->get('RUNNER_DEBUG', '0') !== '1'
        ) {
            return;
        }

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
