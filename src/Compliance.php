<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\ServiceProvider\ApplicationServiceProvider;
use Ghostwriter\Container\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class Compliance
{
    /**
     * @var string
     */
    public const string BLACK_LIVES_MATTER = '<fg=white;bg=black;options=bold>#Black<fg=red;bg=black;options=bold>Lives</>Matter</>';

    /**
     * @var string
     */
    public const string LOGO = <<<'CODE_SAMPLE'
    <fg=red;bg=black;options=bold>
      ____                      _ _
     / ___|___  _ __ ___  _ __ | (_) __ _ _ __   ___ ___
    | |   / _ \| '_ ` _ \| '_ \| | |/ _` | '_ \ / __/ _ \
    | |__| (_) | | | | | | |_) | | | (_| | | | | (_|  __/
     \____\___/|_| |_| |_| .__/|_|_|\__,_|_| |_|\___\___|
                         |_|     %s
    </>%s
    CODE_SAMPLE;

    /**
     * @var string
     */
    public const string NAME = '<info>Compliance - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions.</info>';

    /**
     * @var string
     */
    public const string PACKAGE = 'ghostwriter/compliance';

    public static function main(): void
    {
        (static function (Container $container) {
            $container->provide(ApplicationServiceProvider::class);

            $container->get(Application::class)->run(
                $container->get(InputInterface::class),
                $container->get(OutputInterface::class)
            );
        })(Container::getInstance());
    }
}
