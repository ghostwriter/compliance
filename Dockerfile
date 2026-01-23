# syntax=docker/dockerfile:1
FROM ghcr.io/ghostwriter/php:8.5

LABEL "org.opencontainers.image.title"="Compliance"
LABEL "org.opencontainers.image.description"="Compliance Automation for PHP - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions."
LABEL "org.opencontainers.image.authors"="Nathanael Esayeas <nathanael.esayeas@protonmail.com>, github.com/ghostwriter"
LABEL "org.opencontainers.image.source"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.url"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.licenses"="BSD-3-Clause"

WORKDIR /srv/workspace

COPY composer.* /srv/workspace/
COPY bin /srv/workspace/bin/
COPY config /srv/workspace/config/
COPY src /srv/workspace/src/
# COPY tools /srv/workspace/tools/

RUN composer install --no-autoloader --no-cache --no-dev --no-interaction  --verbose \
&& composer dump-autoload --classmap-authoritative --no-cache --no-dev --no-interaction --verbose

# VERBOSITY = DEBUG
ENV SHELL_VERBOSITY=3

ENTRYPOINT ["/srv/workspace/bin/compliance"]
