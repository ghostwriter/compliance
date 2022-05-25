FROM ghcr.io/ghostwriter/php:8.1-composer

LABEL "org.opencontainers.image.title"="Compliance Automation"
LABEL "org.opencontainers.image.description"="Compliance Automation for PHP - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions."
LABEL "org.opencontainers.image.authors"="Nathanael Esayeas <nathanael.esayeas@protonmail.com>, github.com/ghostwriter"
LABEL "org.opencontainers.image.source"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.url"="https://github.com/ghostwriter/compliance"
LABEL "org.opencontainers.image.licenses"="BSD-3-Clause"

WORKDIR /github/workspace

COPY / /github/workspace

RUN composer install --no-autoloader --no-interaction

ENTRYPOINT ["/github/workspace/bin/compliance"]
