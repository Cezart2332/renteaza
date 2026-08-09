#!/usr/bin/env bash
set -e

cd /var/www/html

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
APP_INIT="${APP_INIT:-0}"

log() { echo "[entrypoint] $*"; }

# ---------------------------------------------------------------- .env
if [ ! -f .env ]; then
    log ".env lipseste, il creez din .env.docker"
    cp .env.docker .env
fi

# ------------------------------------------------------- dependinte PHP
if [ "$APP_INIT" = "1" ]; then
    if [ ! -f vendor/autoload.php ] || [ composer.lock -nt vendor/autoload.php ]; then
        log "rulez composer install"
        composer install --no-interaction --prefer-dist --no-progress
    fi
else
    # containerele secundare (queue) asteapta ca 'app' sa termine composer install
    while [ ! -f vendor/autoload.php ]; do
        log "astept vendor/autoload.php..."
        sleep 3
    done
fi

# ------------------------------------------------------------ APP_KEY
if ! grep -qE '^APP_KEY=base64:.+' .env; then
    log "generez APP_KEY"
    php artisan key:generate --force
fi

# --------------------------------------------------- directoare runtime
mkdir -p \
    storage/app/private \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
chmod -R a+rwX storage bootstrap/cache 2>/dev/null || true

# ------------------------------------------------------- astept MySQL
log "astept MySQL pe ${DB_HOST}:${DB_PORT}"
until mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" --silent >/dev/null 2>&1; do
    sleep 2
done
log "MySQL e sus"

# ------------------------------------------- migrari / seed / storage
if [ "$APP_INIT" = "1" ]; then
    php artisan storage:link || true

    log "rulez migrarile"
    php artisan migrate --force

    # Seederele sunt idempotente, deci ruleaza la fiecare pornire. (Varianta
    # veche folosea un marker storage/.seeded, dar acela traieste pe disc si
    # supravietuieste unui `down -v`: baza era stearsa, markerul ramanea, si
    # porneai fara roluri si fara cont de admin.)
    log "rulez seederele"
    if ! php artisan db:seed --force; then
        log "ATENTIE: seederele au esuat, continui oricum"
    fi

    # in dev vrem config/route cache curate, altfel .env-ul nou nu se vede
    php artisan config:clear >/dev/null 2>&1 || true
    php artisan route:clear  >/dev/null 2>&1 || true
    php artisan view:clear   >/dev/null 2>&1 || true
fi

log "pornesc: $*"
exec "$@"
