<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\Command\MatrixCommand;
use Ghostwriter\Compliance\ServiceProvider\ApplicationServiceProvider;
use Ghostwriter\Container\Contract\ContainerInterface;
use RuntimeException;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Throwable;
use const PHP_EOL;
use function ini_get;
use function ini_set;
use function sprintf;

final class Compliance extends SymfonyApplication
{
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

    private const BLACK_LIVES_MATTER = '<fg=white;bg=black;options=bold>#Black<fg=red;bg=black;options=bold>Lives</>Matter</>';

    private const NAME = '<info>Compliance - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions.</info>';

    private const PACKAGE = 'ghostwriter/compliance';

    private ContainerInterface $container;

    /**
     * @throws Throwable
     */
    public function __construct(ContainerInterface $container)
    {
        $version = InstalledVersions::getPrettyVersion(self::PACKAGE);

        if (null === $version) {
            throw new RuntimeException('Invalid version!');
        }

        parent::__construct(self::NAME, $version);

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        /** @var class-string<Command> $command */
        foreach ($container->tagged(Command::class) as $command) {
            $this->add($container->get($command));
        }

        $this->setDefaultCommand(MatrixCommand::getDefaultName());
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getHelp(): string
    {
        return sprintf(self::LOGO, self::BLACK_LIVES_MATTER, PHP_EOL . parent::getHelp());
    }

    public function getLongVersion(): string
    {
        return sprintf(
            '<fg=cyan;bg=black;options=bold>%s</>: <fg=magenta;bg=black;options=bold>%s</>',
            self::PACKAGE,
            $this->getVersion()
        );
    }

    /**
     * @throws Throwable
     */
    public static function main(ContainerInterface $container): void
    {
        if (! ini_get('date.timezone')) {
            ini_set('date.timezone', 'UTC');
        }
        $container->build(ApplicationServiceProvider::class);
        $container->get(self::class)->run();
    }
}
