<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Listener;

use Ghostwriter\Compliance\Contract\EventListenerInterface;
use Ghostwriter\Compliance\Event\GitHubEvent;
use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Option\Filesystem;

final readonly class GitHubListener implements EventListenerInterface
{
    public function __construct(
        private Filesystem $filesystem,
    ) {
    }
    public function __invoke(GitHubEvent $event): void
    {
        $input = $event->getInput();

        $output = $event->getOutput();

        $output->writeln(sprintf(
            Compliance::LOGO,
            Compliance::BLACK_LIVES_MATTER,
            Compliance::NAME,
        ));

        $eventName = $input->getArgument('event');

        $output->writeln(sprintf('GitHub Event Name: <info>%s</info>', $eventName));
        
        $payload = $input->getArgument('payload');

        if ($this->filesystem->missing($payload)) {
            $output->writeln(sprintf('GitHub Payload File Does Not Exist: <comment>%s</comment>', $payload));

            return;
        }

        $output->writeln(sprintf('GitHub Payload File Exists: <info>%s</info>', $payload));

        $content = $this->filesystem->read($payload);

        $output->writeln(sprintf('GitHub Payload File Content: %s<info>%s</info>', PHP_EOL, $content));
    }
}
