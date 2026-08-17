FROM dunglas/frankenphp:php8.4-bookworm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN install-php-extensions pdo_mysql


WORKDIR /srv/app

# Copy project files
COPY . /srv/app

# Install system deps, composer and node are available in the base image
RUN apt-get update \
    && apt-get install -y git unzip zip libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP deps and composer dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Build frontend assets
RUN if [ -f package.json ]; then npm install && npm run build || true; fi

# Generate app key (will be overwritten by ENV in Render if provided)
RUN php artisan key:generate --force || true

EXPOSE 8080

# Run migrations then start the built-in server on the container port
CMD ["sh", "-lc", "php artisan migrate --force || true && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
