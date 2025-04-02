FROM ghcr.io/at-cloud-pro/caddy-php:4.0.0 AS app
ENV APP_VERSION="3.30.0"
RUN apk update
COPY . /app
RUN composer install

FROM app AS development
ENV APP_ENV="dev"
RUN apk update && apk save git
ENTRYPOINT ["./docker/dev/entrypoint"]

FROM app AS ci
ENV APP_ENV="test"
ENTRYPOINT ["./docker/ci/entrypoint"]

FROM app AS production
ENV APP_ENV="prod"
RUN composer install --no-dev --no-interaction
ENTRYPOINT ["./docker/prod/entrypoint"]