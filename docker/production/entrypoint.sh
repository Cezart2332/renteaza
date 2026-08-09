#!/usr/bin/env bash
set -e

cd /var/www/html

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-false}"

log() { echo "[entrypoint] $*"; }

if [ -z "${APP_KEY:-}" ]; then
    log "EROARE: APP_KEY nu e setat. Genereaza unul si pune-l in Coolify:"
    log "  php artisan key:generate --show"
    exit 1
fi

# --------------------------------------------------------- directoare runtime
# storage/app vine dintr-un volum persistent, deci poate fi gol la prima pornire
mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# pozele de check-in folosesc disk-ul local 'public' -> are nevoie de symlink
if [ ! -L public/storage ]; then
    php artisan storage:link --force >/dev/null 2>&1 || true
fi

# --------------------------------------------------------------- astept MySQL
log "astept MySQL pe ${DB_HOST}:${DB_PORT}"
for _ in $(seq 1 60); do
    if mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" --silent >/dev/null 2>&1; then
        break
    fi
    sleep 2
done
if ! mysqladmin ping -h "$DB_HOST" -P "$DB_PORT" --silent >/dev/null 2>&1; then
    log "EROARE: MySQL nu raspunde dupa 120s"
    exit 1
fi
log "MySQL e sus"

# ------------------------------------------------------------------- migrari
# Ruleaza doar in containerul 'app', ca sa nu porneasca doua migrari simultan.
if [ "$RUN_MIGRATIONS" = "true" ]; then
    log "rulez migrarile"
    php artisan migrate --force --no-interaction
fi

# --------------------------------------------------------------------- cache
# NU rulam route:cache: routes/web.php si routes/user.php definesc rute cu
# closure, iar Laravel nu le poate serializa ("Unable to prepare route ...").
php artisan config:cache
php artisan view:cache
php artisan event:cache

chown -R www-data:www-data bootstrap/cache

log "pornesc: $*"
exec "$@"
