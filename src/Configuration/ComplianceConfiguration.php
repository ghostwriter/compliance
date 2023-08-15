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
     * @param array<class-string<ToolInterface>,array<int,list<string>>> $checks
     * @param array<class-string<ToolInterface>|int|string,array<int,list<string>>|int|string> $skips
     */
    public function __invoke(array $checks, array $skips): void
    {
        foreach ($checks as $tool => $option) {
            foreach ($option as $phpVersion => $composerDependencies) {
                if ($phpVersion === PhpVersion::ANY) {
                    foreach (PhpVersion::SUPPORTED as $supportedPhpVersion) {
                        foreach ($composerDependencies as $dependency) {
                            if (in_array($dependency, $skips[$tool][$supportedPhpVersion] ?? [], true)) {
                                continue;
                            }

                            $this->container->set(
                                $this->getKey($tool, $supportedPhpVersion, $dependency),
                                true
                            );
                        }
                    }
                    continue;
                }

                foreach ($composerDependencies as $dependency) {
                    if (array_key_exists($phpVersion, $skips[$tool] ?? [])) {
                        if (in_array($dependency, $skips[$tool][$phpVersion], true)) {
                            continue;
                        }
                    }

                    $this->container->set(
                        $this->getKey($tool, $phpVersion, $dependency),
                        true
                    );
                }
            }
        }
    }

    private function getKey(string $tool, int $phpVersion, string $dependency): string
    {
        return $tool . '.' . PhpVersion::TO_STRING[$phpVersion] . '.' . $dependency;
    }
}
