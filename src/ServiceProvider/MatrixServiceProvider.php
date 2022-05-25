<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\ValueObject\Tool;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;

use Symfony\Component\Finder\Finder;
use function dirname;
use function sprintf;
use function str_replace;

final class MatrixServiceProvider implements ServiceProviderInterface
{
    public function __construct(private Finder $finder)
    {
    }

    public function __invoke(ContainerInterface $container): void
    {
        $finder = clone $this->finder;

        $finder->files()
            ->in(dirname(__DIR__) . '/Tool/')
            ->notName('Abstract*.php')
            ->sortByName();

        foreach ($finder->getIterator() as $splFileInfo) {
            $tool = sprintf(
                '%s%s',
                str_replace('ServiceProvider', 'Tool', __NAMESPACE__ . '\\'),
                $splFileInfo->getBasename('.php')
            );

            $container->bind($tool);
            $container->tag($tool, [Tool::class]);
        }
    }
}
