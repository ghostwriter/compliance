<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Composer\Json;

use Composer\InstalledVersions;
use Generator;
use Ghostwriter\Compliance\Composer\DependencyName;
use Ghostwriter\Compliance\Composer\DependencyVersion;
use Ghostwriter\Compliance\Composer\Extension;
use Ghostwriter\Compliance\Composer\License;
use Ghostwriter\Compliance\Composer\Package;
use Ghostwriter\Compliance\Composer\RequireDevList;
use Ghostwriter\Compliance\Composer\RequireList;
use Ghostwriter\Compliance\Composer\RequirePhp;
use Ghostwriter\Compliance\Composer\Resolver\InstalledVersionsResolver;
use Ghostwriter\Compliance\Interface\Composer\PhpVersionConstraintInterface;
use Ghostwriter\Json\Interface\JsonInterface;

final readonly class ComposerJson
{
    private Package $package;

    private PhpVersionConstraintInterface $phpVersionConstraint;

    private RequireDevList $requireDevList;

    private RequireList $requireList;

    /** @param array<int,mixed> $contents */
    public function __construct(
        private string $path,
        private array $contents,
        private JsonInterface $json,
        private InstalledVersionsResolver $installedVersionsResolver
    ) {
        $this->package = new Package(
            new DependencyName($contents['name']),
            new DependencyVersion('dev-main'),
            $this->json
        );

        $this->phpVersionConstraint = RequirePhp::new($contents['require']['php'] ?? '*');
        $this->requireList = RequireList::new($contents['require'] ?? [], $this->json);
        $this->requireDevList = RequireDevList::new($contents['require-dev'] ?? [], $this->json);

        // $configPlatformPhp = $contents['config']['platform']['php'] ?? null;

        // $this->phpVersionConstraint = null === $configPlatformPhp
        //     ? new RequirePhp($contents['require']['php'] ?? '*')
        //     : new ConfigPlatformPhp($configPlatformPhp);

        // dd([
        //     $this
        // ]);
    }

    public function getComposerJsonPath(): string
    {
        return $this->path;
    }

    /** @return array<int,mixed> */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function getLicense(): License
    {
        return License::new($this->contents['license']);
    }

    public function getPackage(): Package
    {
        return $this->package;
    }

    public function getPhpVersionConstraint(): PhpVersionConstraintInterface
    {
        return $this->phpVersionConstraint;
    }

    public function getRequire(): RequireList
    {
        return $this->requireList;
    }

    public function getRequireDev(): RequireDevList
    {
        return $this->requireDevList;
    }

    /** @return Generator<Extension> */
    public function getRequiredPhpExtensions(): Generator
    {
        foreach ($this->requireList as $dependency) {
            if (! $dependency instanceof Extension) {
                continue;
            }

            yield $dependency;
        }

        foreach ($this->requireDevList as $dependency) {
            if (! $dependency instanceof Extension) {
                continue;
            }

            yield $dependency;
        }
    }

    public function getVersion(): DependencyVersion
    {
        return new DependencyVersion(
            $this->contents['version']
            ?? InstalledVersions::getPrettyVersion($this->contents['name'])
        );
    }

    // public function getAutoload(): Autoload
    // {
    //     return new Autoload($this->contents['autoload']);
    // }

    // public function getAutoloadDev(): Autoload
    // {
    //     return new Autoload($this->contents['autoload-dev']);
    // }

    // public function getScripts(): Scripts
    // {
    //     return new Scripts($this->contents['scripts']);
    // }

    // public function getAuthors(): Authors
    // {
    //     return new Authors($this->contents['authors']);
    // }

    // public function getSupport(): Support
    // {
    //     return new Support($this->contents['support']);
    // }

    // public function getBin(): Bin
    // {
    //     return new Bin($this->contents['bin']);
    // }

    // public function getConflicts(): Conflicts
    // {
    //     return new Conflicts($this->contents['conflicts']);
    // }

    // public function getReplace(): Replace
    // {
    //     return new Replace($this->contents['replace']);
    // }

    // public function getRepositories(): Repositories
    // {
    //     return new Repositories($this->contents['repositories']);
    // }

    // public function getExtra(): Extra
    // {
    //     return new Extra($this->contents['extra']);
    // }

    // public function getKeywords(): Keywords
    // {
    //     return new Keywords($this->contents['keywords']);
    // }

    // public function getHomepage(): Homepage
    // {
    //     return new Homepage($this->contents['homepage']);
    // }

    // public function getDescription(): Description
    // {
    //     return new Description($this->contents['description']);
    // }

    // public function getFunding(): Funding
    // {
    //     return new Funding($this->contents['funding']);
    // }
}
