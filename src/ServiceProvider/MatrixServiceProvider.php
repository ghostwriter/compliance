<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\Contract\ToolInterface;
use Ghostwriter\Container\ContainerInterface;
use Ghostwriter\Container\ServiceProviderInterface;

use Symfony\Component\Finder\Finder;
use function dirname;
use function sprintf;
use function str_replace;

final class MatrixServiceProvider implements ServiceProviderInterface
{
    public function __construct(
        private Finder $finder
    ) {
    }

    public function __invoke(ContainerInterface $container): void
    {
        $finder = clone $this->finder;

        $finder->files()
            ->in(dirname(__DIR__) . '/Tool/')
            ->notName('Abstract*.php')
            ->sortByName();

        foreach ($finder->getIterator() as $splFileInfo) {
            $container->bind(
                sprintf(
                    '%s%s',
                    str_replace('ServiceProvider', 'Tool', __NAMESPACE__ . '\\'),
                    $splFileInfo->getBasename('.php')
                ),
                null,
                [ToolInterface::class]
            );
        }
    }
}
