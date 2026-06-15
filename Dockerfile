# syntax=docker/dockerfile:1
FROM ghcr.io/ghostwriter/php:8.5

LABEL "org.opencontainers.image.title"="Compliance"
LABEL "org.opencontainers.image.description"="Compliance Automation for PHP - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions."
LABEL "org.opencontainers.image.authors"="Nathanael Esayeas <nathanael.esayeas@protonmail.com>, github.com/ghostwriter"
LABEL "org.opencontainers.image.source"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.url"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.licenses"="BSD-3-Clause"

WORKDIR /srv/workspace

COPY bin /srv/workspace/bin/
COPY composer.* /srv/workspace/
COPY LICENSE /srv/workspace/LICENSE
COPY src /srv/workspace/src/

RUN composer install --no-autoloader --no-cache --no-dev --no-interaction --no-plugins --no-scripts --verbose \
&& composer dump-autoload --classmap-authoritative --no-cache --no-dev --no-interaction --no-plugins --no-scripts --verbose

# VERBOSITY = DEBUG
ENV SHELL_VERBOSITY=3

ENTRYPOINT ["/srv/workspace/bin/compliance"]
