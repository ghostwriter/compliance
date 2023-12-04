<?php

declare(strict_types=1);

namespace Ghostwriter\Compliance\Service\Composer;

use Generator;
use Ghostwriter\Compliance\Service\Composer\RequireList;
use Ghostwriter\Compliance\Service\RequirePhp;
use Ghostwriter\Compliance\Service\ConfigPlatformPhp;
use Ghostwriter\Compliance\Service\PhpVersionConstraint;
use Composer\InstalledVersions;

final readonly class ComposerJson {
    private Package $package;
    public function getVersion(): DependencyVersion
    {
        return new DependencyVersion(
            $this->contents['version'] ??
            InstalledVersions::getPrettyVersion($this->contents['name'])
        );
    }

    private PhpVersionConstraint $phpVersionConstraint;
    private array $require;
    private array $requireDev;
    /**
     * @param array<int,mixed> $contents
     */
    public function __construct(
        private string $path,
        private array $contents,
    ) {
        $dependencyName = new DependencyName($contents['name']);

        $this->package = new Package($dependencyName, new DependencyVersion('dev-main'));

        $require = $contents['require'] ?? [];

        foreach($require as $name => $version) {
            $dependencyName = new DependencyName($name);
            $dependencyVersion = new DependencyVersion($version);

            $require[$name] = $dependencyName->isPhpExtension()
            ? new Extension($dependencyName, $dependencyVersion)
            : new Package($dependencyName, $dependencyVersion);
        }

        $requireDev = $contents['require-dev'] ?? [];

        foreach($requireDev as $name => $version) {
            $dependencyName = new DependencyName($name);
            $dependencyVersion = new DependencyVersion($version);

            $requireDev[$name] = $dependencyName->isPhpExtension()
            ? new Extension($dependencyName, $dependencyVersion)
            : new Package($dependencyName, $dependencyVersion);
        }

        // $configPlatformPhp = $contents['config']['platform']['php'] ?? null;

        // $this->phpVersionConstraint = null === $configPlatformPhp
        //     ? new RequirePhp($contents['require']['php'] ?? '*')
        //     : new ConfigPlatformPhp($configPlatformPhp);

        $this->phpVersionConstraint = new RequirePhp($contents['require']['php'] ?? '*');

        $this->require = $require;
        $this->requireDev = $requireDev;

        // dd([
        //     $this
        // ]);
    }
    

    public function getPhpVersionConstraint(): PhpVersionConstraint
    {
        return $this->phpVersionConstraint;
    }
    
    public function getComposerJsonPath(): string
    {
        return $this->path;
    }
    /**
     * @return array<int,mixed>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    public function getPackage(): Package
    {
        return $this->package;
    }

    public function getLicense(): License
    {
        return new License($this->contents['license']);
    }

    public function getRequire(): RequireList
    {
        return new RequireList($this->require);
    }

    public function getRequireDev(): RequireDevList
    {
        return new RequireDevList($this->requireDev);
    }

    /**
     * @return Generator<Extension>
     */
    public function getRequiredPhpExtensions(): Generator
    {
        foreach ($this->getRequire() as $dependency) {
            if (! $dependency instanceof Extension) {
                continue;
            }

            yield $dependency;
        }

        foreach ($this->getRequireDev() as $dependency) {
            if (! $dependency instanceof Extension) {
                continue;
            }

            yield $dependency;
        }
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
