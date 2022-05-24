FROM ghcr.io/ghostwriter/php:8.1-composer

LABEL "com.github.actions.name"="Compliance"
LABEL "com.github.actions.description"="Compliance Automation for PHP - Automatically configure and execute multiple CI/CD & QA Tests via GitHub Actions."
LABEL "com.github.actions.icon"="triangle"
LABEL "com.github.actions.color"="green"

WORKDIR /app

COPY / /app

RUN composer install --no-autoloader --no-interaction

ENTRYPOINT ["/app/bin/compliance"]