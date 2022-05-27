# Compliance

[![Compliance](https://github.com/ghostwriter/compliance/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/compliance/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/compliance?color=8892bf)](https://www.php.net/supported-versions)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/compliance/coverage.svg)](https://shepherd.dev/github/ghostwriter/compliance)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/compliance)](https://packagist.org/packages/ghostwriter/compliance)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/compliance?color=blue)](https://packagist.org/packages/ghostwriter/compliance)

Compliance Automation for PHP - Automatically configure and execute multiple `CI/CD` & `QA Testing` tools on any platform via `GitHub Action`.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/compliance
```

## Usage

```bash
# Create `.github/workflows/compliance.yml` workflow file
compliance init

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

docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance init
docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance matrix
docker run -v $(PWD):/app -w=/app ghcr.io/ghostwriter/compliance check {job}

# Use as base image in Dockerfile:

FROM ghcr.io/ghostwriter/compliance:v1
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

[![ghostwriter's GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsors&logo=GitHub%20Sponsors)](https://github.com/sponsors/ghostwriter)

Maintaining open source software is a thankless, time-consuming job.

Sponsorships are one of the best ways to contribute to the long-term sustainability of an open-source licensed project.

Please consider giving back, to fund the continued development of `ghostwriter/compliance`, by sponsoring me here on GitHub.

[[Become a GitHub Sponsor](https://github.com/sponsors/ghostwriter)]

### For Developers

Please consider helping your company become a GitHub Sponsor, to support the open-source licensed project that runs your business.

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/compliance/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
