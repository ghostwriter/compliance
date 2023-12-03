<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Service\Finder;
use Ghostwriter\Compliance\ToolInterface;
use Ghostwriter\Container\Interface\ContainerInterface;
use Ghostwriter\Container\Interface\ServiceProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use function dirname;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_replace;

final readonly class MatrixServiceProvider implements ServiceProviderInterface
{
    public function __construct(
        private Finder $finder,
    ) {
    }

    public function __invoke(ContainerInterface $container): void
    {
        $files = $this->finder->findIn(dirname(__DIR__) . '/Tool/');
        foreach ($files as $file) {
            $path = $file->getPathname();

            if (str_contains($path, 'Abstract') || !str_ends_with($path, '.php')) {
                continue;
            }

            $service = sprintf(
                '%s%s',
                str_replace(
                    'ServiceProvider',
                    'Tool',
                    __NAMESPACE__ . '\\'
                ),
                $file->getBasename('.php')
            );

            $container->tag($service, [ToolInterface::class]);
        }
    }
}
