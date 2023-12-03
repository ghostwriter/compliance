# Compliance

[![Automation](https://github.com/ghostwriter/compliance/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/compliance/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/compliance?color=8892bf)](https://www.php.net/supported-versions)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/compliance/coverage.svg)](https://shepherd.dev/github/ghostwriter/compliance)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/compliance)](https://packagist.org/packages/ghostwriter/compliance)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/compliance?color=blue)](https://packagist.org/packages/ghostwriter/compliance)

Compliance Automation for PHP - Automatically configure and execute multiple `CI/CD` & `QA Testing` tools on any platform via `GitHub Action`.

> **Warning**
> 
> This project is not finished yet, work in progress.
>

## Workflow

```yml
# .github/workflows/compliance.yml
name: Compliance

on:
  pull_request:
    branches:
      - "**"
  push:
    branches:
      - "main"
      - "[0-9]+.[0-9]+.x" # 1.2.x
      - "v[0-9]+" # v1
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

## Usage

```bash
# Create `.github/workflows/compliance.yml` workflow file
compliance workflow

# Create `./compliance.php` configuration file
compliance config

# Determine CI Jobs for GitHub Actions
compliance matrix

# Executes a specific Job
compliance check {job}
```

## Docker

``` bash
# Install from the command line:

docker pull ghcr.io/ghostwriter/compliance:v1

# Usage from the command line:

docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance workflow
docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance config
docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance matrix
docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance check {job}

# Use as base image in Dockerfile:

FROM ghcr.io/ghostwriter/compliance:v1
```

## Supported Tools

``` php
Ghostwriter\Compliance\Tool\ComposerRequireChecker;
Ghostwriter\Compliance\Tool\ECS;
Ghostwriter\Compliance\Tool\Infection;
Ghostwriter\Compliance\Tool\PHPBench;
Ghostwriter\Compliance\Tool\PHPCS;
Ghostwriter\Compliance\Tool\PHPCSFixer;
Ghostwriter\Compliance\Tool\PHPUnit;
Ghostwriter\Compliance\Tool\Psalm;
Ghostwriter\Compliance\Tool\Rector;
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
- [`mlocati/docker-php-extension-installer`](https://github.com/mlocati/docker-php-extension-installer)
- [`shivammathur/setup-php`](https://github.com/shivammathur/setup-php)
- [All Contributors](https://github.com/ghostwriter/compliance/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
