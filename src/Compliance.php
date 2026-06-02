<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\Interface\ComplianceInterface;
use Ghostwriter\Container\Container;
use Ghostwriter\Container\Interface\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final readonly class Compliance implements ComplianceInterface
{
    /** @var string */
    public const string BLACK_LIVES_MATTER = '<fg=white;bg=black;options=bold>#Black<fg=red;bg=black;options=bold>Lives</>Matter</>';

    /** @var string */
    public const string DESCRIPTION = 'Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions.';

    /** @var string */
    public const string LOGO = <<<'EOD'
        <fg=red;bg=black;options=bold>
          ____                      _ _
         / ___|___  _ __ ___  _ __ | (_) __ _ _ __   ___ ___
        | |   / _ \| '_ ` _ \| '_ \| | |/ _` | '_ \ / __/ _ \
        | |__| (_) | | | | | | |_) | | | (_| | | | | (_|  __/
         \____\___/|_| |_| |_| .__/|_|_|\__,_|_| |_|\___\___|
                             |_|     %s
        </>%s
        EOD;

    /** @var string */
    public const string NAME = 'Compliance';

    /** @var string */
    public const string PACKAGE = 'ghostwriter/compliance';

    public function __construct(
        private Application $application,
        private ContainerInterface $container,
    ) {}

    /** @throws Throwable */
    public static function new(): self
    {
        return Container::getInstance()->get(self::class);
    }

    /** @throws Throwable */
    public function run(array $arguments = []): int
    {
        $this->container->set(ArgvInput::class, new ArgvInput($arguments));

        return $this->application->run(
            $this->container->get(InputInterface::class),
            $this->container->get(OutputInterface::class)
        );
    }
}
