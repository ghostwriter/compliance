<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance;

use Composer\InstalledVersions;
use Ghostwriter\Compliance\ServiceProvider\ApplicationServiceProvider;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\EventDispatcher\Contract\DispatcherInterface;
use RuntimeException;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Throwable;
use function ini_get;
use function ini_set;
use function sprintf;
use function trim;

final class Compliance extends SymfonyApplication
{
    private const BLACK_LIVES_MATTER = '<fg=white;bg=black;options=bold>[#Black</><fg=red;bg=black;options=bold>Lives</><fg=white;bg=black;options=bold>Matter]</>';

    private const NAME = 'Automated Compliance - CI/CD & QA Testing via GitHub Actions.';

    private const PACKAGE = 'ghostwriter/compliance';

    private ContainerInterface $container;

    private DispatcherInterface $dispatcher;

    /**
     * @throws Throwable
     */
    public function __construct(ContainerInterface $container)
    {
        $version = InstalledVersions::getPrettyVersion(self::PACKAGE);

        if (null === $version) {
            throw new RuntimeException('Invalid version!');
        }

        parent::__construct(self::NAME . ' ' . self::BLACK_LIVES_MATTER, $version);

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        /** @var class-string<Command> $command */
        foreach ($container->tagged(Command::class) as $command) {
            $this->add($container->get($command));
        }

        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getLongVersion(): string
    {
        return trim(sprintf(
            '<fg=blue>[</><comment>%s</comment>@<comment>%s</comment><fg=blue>]</><info>%s</info>',
            self::PACKAGE,
            $this->getVersion(),
            $this->getName(),
        ));
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
