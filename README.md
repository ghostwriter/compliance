# Compliance

[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/compliance&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Automation](https://github.com/ghostwriter/compliance/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/compliance/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/compliance?color=8892bf)](https://www.php.net/supported-versions)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/compliance?color=blue)](https://packagist.org/packages/ghostwriter/compliance)

`Automation` - `CI/CD` & `QA Testing`  Test Automation for `PHP` via `GitHub Actions`.

> [!CAUTION]
>
> This project is not finished yet, work in progress.
>

## Todo

- check the composer scripts section to see if the commands exists,
  - and report error/warning if not
  - and skip the job if not

- all tools should have a matching composer script name, i have already hardcoded
  the composer script for now, but this should be configurable (kebab case)
  - the command will be `composer automation:composer-require-checker`
    - or `composer automation:phpunit`
      - I like the `automation:` prefix, that way if you have a `phpunit` script,
      - it will not cause a conflict with the `automation:phpunit` script

- I will import the release automation into this project
- we will import the `composer.json` and `composer.lock` validation into this project
  - we will use the extensions field from the `composer.json` file to determine which PHP extensions to install
  - pass the extensions list as an argument to the docker image/workflow via shivammathur/setup-php@v2
     (looks like this part is already done, but we need to test it.)
  - i did most of this... but i need to test it.
  - we have everything we need to extract information from both composer files

> [!INFO]
>
> Woot woot!! we did it! we have a working prototype! 🎉
>

-- we need to import gpg keys from GitHub secrets `GPG_KEY`,`GPG_FINGERPRINT`
--- to tag the release with a gpg signature
--- to sign binaries with a gpg signature
--- to sign the git commit with a gpg signature (automated composer.json update, if all tests pass)


- we need to add a command to add these commannds to the users composer.json file,
 for each supported tools they have in their composer.json file [`require` and `require-dev`].
 - if it does not exist, we will add it to the `scripts` section.
 - if it exists, continue.

```json
{
    "scripts": {
        "automation:composer-require-checker": [
            "composer-require-checker check --config-file=composer-require-checker.json"
        ],
        "automation:phpunit": [
              "@phpunit --configuration=phpunit.xml --coverage-clover=coverage.xml"
        ]
    }
}
```
  
## Workflow

```yml
# .github/workflows/automation.yml
name: Automation

on:
  pull_request:
  push:
    branches:
      - "main"
      - "[0-9]+.[0-9]+.x" # 1.2.x
      - "v[0-9]+" # v1
  schedule:
    - cron: "0 * * * *" # Runs hourly
  workflow_dispatch: # Manually Trigger workflow

jobs:
  automation:
    uses: ghostwriter/compliance/.github/workflows/automation.yml@v1
    secrets:
      CODECOV_TOKEN: ${{ secrets.CODECOV_TOKEN }}
      GPG_PRIVATE_KEY: ${{ secrets.GPG_PRIVATE_KEY }}
      INFECTION_DASHBOARD_API_KEY: ${{ secrets.INFECTION_DASHBOARD_API_KEY }}
```

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/compliance --dev
```

```bash
Automation - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions. 1.x-dev

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help        Display help for a command
  list        List commands
  matrix      Generates a job matrix for Github Actions.
  workflow    Creates a "automation.yml" workflow file.
```

## Usage

Generates `automation.php` configuration file, if it does not exist,
and determines the job matrix for GitHub Actions.

```bash
vendor/bin/automation matrix
```

Generate `.github/workflows/automation.yml` workflow file for your project.
```bash
vendor/bin/automation workflow
```

## Docker

``` bash
# Install from the command line:

docker pull ghcr.io/ghostwriter/compliance:v1

# Usage from the command line:

docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance workflow
docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance matrix

# Use as base image in Dockerfile:

FROM ghcr.io/ghostwriter/compliance:v1
```

## Supported Tools ?!

``` php

<?php

declare(strict_types=1);

use Ghostwriter\Automation\Automation;
use Ghostwriter\Automation\Enum\ComposerStrategy;
use Ghostwriter\Automation\Enum\OperatingSystem;
use Ghostwriter\Automation\Enum\PhpVersion;
use Ghostwriter\Automation\Enum\Tool;
use Ghostwriter\Automation\Tool\Infection;
use Ghostwriter\Automation\Tool\PHPUnit;
use Ghostwriter\Automation\Tool\Psalm;

return Automation::new()
    ->composerStrategies(...ComposerStrategy::cases()) // ComposerStrategy::LATEST, ComposerStrategy::LOCKED, ComposerStrategy::LOWEST
    ->operatingSystems(...OperatingSystem::cases()) // OperatingSystem::UBUNTU, OperatingSystem::MACOS, OperatingSystem::WINDOWS
    ->phpVersions(...PhpVersion::cases()) // PhpVersion::PHP_54 - PhpVersion::PHP_84
    ->tools(...Tool::cases()) // Tool::Infection, Tool::PHPUnit, Tool::Psalm
    ->skip(OperatingSystem::WINDOWS) // Skip one or more: Composer Strategy, Operating System, PHP Version, or Tool 
;
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email `nathanael.esayeas@protonmail.com` instead of using the issue tracker.

## Sponsors

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [`composer`](https://github.com/composer)
- [`mlocati/docker-php-extension-installer`](https://github.com/mlocati/docker-php-extension-installer)
- [`shivammathur/setup-php`](https://github.com/shivammathur/setup-php)
- [`symfony`](https://github.com/symfony)
- [All Contributors](https://github.com/ghostwriter/compliance/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
