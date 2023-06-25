# syntax=docker/dockerfile:1
FROM ghcr.io/ghostwriter/php:8.2-composer

LABEL "org.opencontainers.image.title"="Compliance"
LABEL "org.opencontainers.image.description"="Compliance Automation for PHP - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions."
LABEL "org.opencontainers.image.authors"="Nathanael Esayeas <nathanael.esayeas@protonmail.com>, github.com/ghostwriter"
LABEL "org.opencontainers.image.source"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.url"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.licenses"="BSD-3-Clause"

WORKDIR /app

COPY composer.* /app/

RUN COMPOSER_CACHE_DIR=/dev/null composer install --no-dev --no-autoloader --no-interaction --verbose

COPY bin /app/bin/
COPY src /app/src/

RUN composer dump-autoload -a --no-dev

# VERBOSITY = DEBUG
ENV SHELL_VERBOSITY=3

ENTRYPOINT ["/app/bin/compliance"]
