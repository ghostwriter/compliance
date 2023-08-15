<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Configuration;

use Ghostwriter\Compliance\Contract\ToolInterface;
use Ghostwriter\Compliance\Option\ComposerDependency;
use Ghostwriter\Compliance\Option\PhpVersion;
use Ghostwriter\Container\ContainerInterface;
use RuntimeException;
use function array_key_exists;

final class ComplianceConfiguration
{
    public function __construct(
        private ContainerInterface $container
    ) {
        $this->container->set(ComposerDependency::CONFIG . '.php', PhpVersion::LATEST);
    }

    public function composerDependency(string $dependency): void
    {
        if (! array_key_exists($dependency, ComposerDependency::OPTIONS)) {
            throw new RuntimeException();
        }

        $this->container->set(ComposerDependency::CONFIG . '.dependency', $dependency);
    }

    /**
     * @param array<ComposerDependency::*> $options
     */
    public function composerOptions(array $options): void
    {
        $this->container->set(ComposerDependency::CONFIG . '.options', $options);
    }

    /**
     * @param array<int|array<class-string<ToolInterface>,list<int>> $array
     */
    public function configure(array $array): void
    {
    }

    public function phpVersion(int $phpVersion): void
    {
        $this->container->remove(ComposerDependency::CONFIG . '.php');
        $this->container->set(ComposerDependency::CONFIG . '.php', $phpVersion);
    }

    /**
     * @param array<int|array<class-string<ToolInterface>,list<int>> $array
     */
    public function skip(array $array): void
    {
    }
}
