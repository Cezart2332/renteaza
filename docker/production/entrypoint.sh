#!/usr/bin/env bash
set -e

cd /var/www/html

DB_HOST="${DB_HOST:-mariadb}"
DB_PORT="${DB_PORT:-3306}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-false}"
RUN_SEEDERS="${RUN_SEEDERS:-false}"

log() { echo "[entrypoint] $*"; }

# APP_KEY nu se genereaza automat aici: fiecare pornire ar produce alta cheie,
# sesiunile ar pica la fiecare redeploy, iar datele criptate ar deveni ilizibile.
# Mai bine se opreste zgomotos decat sa porneasca cu o cheie efemera.
if [ -z "${APP_KEY:-}" ]; then
    log "EROARE: APP_KEY nu e setat."
    log "Genereaza o cheie (pe serverul tau sau pe laptop, nu e nevoie de container):"
    log ""
    log "  echo \"base64:\$(openssl rand -base64 32)\""
    log ""
    log "si pune rezultatul in Coolify -> Environment Variables, ca APP_KEY."
    exit 1
fi

# Formatul corect e 'base64:' + 32 de octeti codificati. Fara prefix, Laravel
# crapa mai tarziu cu un mesaj criptic despre cipher sau lungimea cheii.
case "$APP_KEY" in
    base64:*) ;;
    *)
        log "EROARE: APP_KEY nu incepe cu 'base64:'."
        log "Valoarea trebuie sa arate asa: base64:xxxxxxxx...= (44 de caractere dupa prefix)"
        exit 1
        ;;
esac

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

# ------------------------------------------------------------------- seedere
# Ruleaza la fiecare deploy. RoleSeeder si AdminUserSeeder sunt idempotente:
# reconstruiesc rolurile si contul de admin fara sa duplice nimic.
#
# Esecul seederelor NU opreste pornirea aplicatiei: migrarile au trecut deja,
# iar un site care ramane sus e mai important decat datele de bootstrap. Eroarea
# apare in logurile Coolify.
if [ "$RUN_SEEDERS" = "true" ]; then
    log "rulez seederele"
    if ! php artisan db:seed --force --no-interaction; then
        log "ATENTIE: seederele au esuat, pornesc aplicatia oricum (vezi eroarea de mai sus)"
    fi
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
