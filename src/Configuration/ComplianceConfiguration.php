<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Configuration;

use Ghostwriter\Compliance\Configuration\ValueObject\ComplianceConfigurationOption;
use Ghostwriter\Compliance\ValueObject\ComposerDependency;
use Ghostwriter\Compliance\ValueObject\ComposerOptions;
use Ghostwriter\Compliance\ValueObject\PhpVersion;
use Ghostwriter\Container\Contract\ContainerInterface;
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
//        COMPOSER_DEPENDENCY
//        ConfigurationOption::class
        $this->container->set($this->option(ComplianceConfigurationOption::COMPOSER_DEPENDENCY), $dependency);
    }

    /**
     * @param array<ComposerOptions::*> $options
     */
    public function composerOptions(array $options): void
    {
        $this->container->set($this->option(ComplianceConfigurationOption::COMPOSER_OPTIONS), $options);
    }

    public function getExclude(): array
    {
        return $this->exclude;
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
        $this->container->set(ComplianceConfigurationOption::PHP_VERSION, $phpVersion);
    }

    public function skip(array $array): void
    {
        /** @var string $value */
        foreach ($array as $value) {
            $this->exclude[] = $value;
        }
    }

    private function option(string $key): string
    {
        return sprintf('%s::%s%s', Option::CONFIG, $key, Option::CONFIG);
    }
}
