<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Extension;

use Ghostwriter\Compliance\Command\MatrixCommand;
use Ghostwriter\Compliance\Compliance;
use Ghostwriter\Compliance\Service\Finder;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Symfony\Component\Console\Application as SymfonyApplication;
use Throwable;
use function dirname;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_replace;

/**
 * @implements ExtensionInterface<Compliance>
 */
final readonly class SymfonyApplicationExtension implements ExtensionInterface
{
    public function __construct(
        private Finder $finder,
    ) {
    }
    /**
     * @param SymfonyApplication $service
     *
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container, object $service): SymfonyApplication
    {
        $service->setAutoExit(false);
        $service->setCatchExceptions(false);

        $files = $this->finder->findIn(dirname(__DIR__) . '/Command/');
        foreach ($files as $file) {

            $path = $file->getPathname();

            if (str_contains($path, 'Abstract') || !str_ends_with($path, 'Command.php')) {
                continue;
            }

            $service->add(
                $container->get(
                    sprintf(
                        '%s%s',
                        str_replace(
                            'Extension',
                            'Command',
                            __NAMESPACE__ . '\\'
                        ),
                        $file->getBasename('.php')
                    )
                )
            );
        }

        $service->setDefaultCommand(MatrixCommand::getDefaultName());

        return $service;
    }
}
