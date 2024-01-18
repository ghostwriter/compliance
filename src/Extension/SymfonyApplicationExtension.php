<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Extension;

use Ghostwriter\Compliance\Service\Filesystem;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ExtensionInterface;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use const DIRECTORY_SEPARATOR;
use function dirname;
use function is_a;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_replace;

/**
 * @implements ExtensionInterface<SymfonyApplication>
 */
final readonly class SymfonyApplicationExtension implements ExtensionInterface
{
    public function __construct(
        private Filesystem $filesystem,
    ) {
    }

    /**
     * @param SymfonyApplication $service
     */
    public function __invoke(ContainerInterface $container, object $service): SymfonyApplication
    {
        $service->setAutoExit(false);

        foreach ($this->filesystem->findIn(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Command') as $file) {
            $path = $file->getPathname();

            if (
                str_contains($path, 'Abstract') ||
                str_ends_with($path, 'Trait.php') ||
                ! str_ends_with($path, 'Command.php')
            ) {
                continue;
            }

            $class = sprintf(
                '%s%s',
                str_replace('Extension', 'Command', __NAMESPACE__ . '\\'),
                $file->getBasename('.php')
            );

            if (! is_a($class, Command::class, true)) {
                continue;
            }

            $service->add($container->get($class));
        }

        $service->setDefaultCommand('run');

        return $service;
    }
}
