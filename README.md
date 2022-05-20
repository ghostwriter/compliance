# Automated Compliance

[![Continuous Integration](https://github.com/ghostwriter/automated-compliance/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/ghostwriter/automated-compliance/actions/workflows/continuous-integration.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/automated-compliance?color=8892bf)](https://www.php.net/supported-versions)
[![Type Coverage](https://shepherd.dev/github/ghostwriter/automated-compliance/coverage.svg)](https://shepherd.dev/github/ghostwriter/automated-compliance)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/automated-compliance)](https://packagist.org/packages/ghostwriter/automated-compliance)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/automated-compliance?color=blue)](https://packagist.org/packages/ghostwriter/automated-compliance)

Automatically configure and execute multiple `CI/CD` & `QA Testing` tools on any platform via `GitHub Action`.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/automated-compliance
```

## Usage

```bash
# work in progress
$ automated-compliance init           # Create `.github/workflows/automated-compliance.yml` workflow file
$ automated-compliance matrix         # Determine CI Jobs for GitHub Actions
$ automated-compliance process        # Executes all Jobs (the result of `automated-compliance matrix` command)
$ automated-compliance process {job}  # Executes a specific Job 
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

Please consider giving back, to fund the continued development of `ghostwriter/automated-compliance`, by sponsoring me here on GitHub.

[[Become a GitHub Sponsor](https://github.com/sponsors/ghostwriter)]

### For Developers

Please consider helping your company become a GitHub Sponsor, to support the open-source licensed project that runs your business.

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/automated-compliance/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
