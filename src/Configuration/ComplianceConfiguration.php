<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Configuration;

use Ghostwriter\Compliance\ValueObject\ComposerDependency;
use Ghostwriter\Compliance\ValueObject\PhpVersion;
use Ghostwriter\Container\Contract\ContainerInterface;
use RuntimeException;
use function array_key_exists;
use function sprintf;

final class ComplianceConfiguration
{
    private ContainerInterface $container;

    private array $exclude = [];

    private array $include = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ComposerDependency::* $dependency
     */
    public function composerDependency(string $dependency): void
    {
        if (! array_key_exists($dependency, ComposerDependency::OPTIONS)) {
            throw new RuntimeException();
        }

        $this->container->set(ComposerDependency::CONFIG.'.dependency', $dependency);
    }

    /**
     * @param array<ComposerDependency::*> $options
     */
    public function composerOptions(array $options): void
    {
        $this->container->set(ComposerDependency::CONFIG.'.options', $options);
    }

    public function getExclude(): array
    {
        return $this->exclude;
    }

    public function getInclude(): array
    {
        return $this->include;
    }

    public function paths(array $array)
    {
        /** @var string $value */
        foreach ($array as $value) {
            $this->include[] = $value;
        }
    }

    /**
     * @param PhpVersion::* $phpVersion
     */
    public function phpVersion(int $phpVersion): void
    {
        $this->container->set(ComposerDependency::CONFIG.'.php', $phpVersion);
    }

    public function skip(array $array): void
    {
        /** @var string $value */
        foreach ($array as $value) {
            $this->exclude[] = $value;
        }
    }
}
