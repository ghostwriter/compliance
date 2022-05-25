<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\ServiceProvider;

use Ghostwriter\Compliance\ValueObject\Tool;
use Ghostwriter\Container\Contract\ContainerInterface;
use Ghostwriter\Container\Contract\ServiceProviderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function dirname;
use function preg_match;
use function sprintf;
use function str_replace;
use function strtolower;

final class MatrixServiceProvider implements ServiceProviderInterface
{
    public function __invoke(ContainerInterface $container): void
    {
        /** @var SplFileInfo $splFileInfo */
        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(dirname(__DIR__) . '/Tool/')
            ) as $splFileInfo
        ) {
            /** @var string $path */
            $path = $splFileInfo->getRealPath();

            if (! preg_match('#\.php$#', $path)) {
                continue;
            }

            if (str_contains(strtolower($splFileInfo->getBasename()), 'abstract')) {
                continue;
            }

            $component = sprintf(
                '%s%s',
                str_replace('ServiceProvider', 'Tool', __NAMESPACE__ . '\\'),
                $splFileInfo->getBasename('.php')
            );

            $container->bind($component);
            $container->tag($component, [Tool::class]);
        }
    }
}
