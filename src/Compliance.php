<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Ghostwriter\Compliance\ServiceProvider\ApplicationServiceProvider;
use Ghostwriter\Container\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class Compliance
{
    /**
     * @var string
     */
    public const BLACK_LIVES_MATTER = '<fg=white;bg=black;options=bold>#Black<fg=red;bg=black;options=bold>Lives</>Matter</>';

    /**
     * @var string
     */
    public const CURRENT_WORKING_DIRECTORY = 'Compliance.CurrentWorkingDirectory';

    /**
     * @var string
     */
    public const LOGO = <<<'CODE_SAMPLE'
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
    public const NAME = '<info>Compliance - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions.</info>';

    /**
     * @var string
     */
    public const PACKAGE = 'ghostwriter/compliance';


    /**
     * @var string
     */
    public const WORKFLOW_TEMPLATE = 'Compliance.WorkflowTemplate';

    /**
     * @throws Throwable
     */
    public static function main(
        ?InputInterface $input = null,
        ?OutputInterface $output = null,
    ): void
    {
        $container = Container::getInstance();

        $container->provide(ApplicationServiceProvider::class);

        if ($input instanceof InputInterface) {
            $container->set(InputInterface::class, $input);
        }

        if ($output instanceof OutputInterface) {
            $container->set(OutputInterface::class, $output);
        }

        $container->get(Application::class)
                ->run($container->get(InputInterface::class), $container->get(OutputInterface::class));
    }
}
