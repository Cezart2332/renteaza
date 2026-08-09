# RENTeaza — rulare in Docker

Stack complet: Laravel 12 + Inertia/Vue 3 + Vite, MySQL 8, MinIO (inlocuieste S3),
Mailpit (inlocuieste SMTP-ul real) si un worker de cozi.

## Pornire

```bash
docker compose up --build
```

Prima pornire dureaza cateva minute: se construieste imaginea PHP, ruleaza
`composer install`, `npm ci`, migrarile si seederele. Cand vezi in log
`Server running on [http://0.0.0.0:8000]` si `VITE ready`, e gata.

| Serviciu            | URL                                            |
| ------------------- | ---------------------------------------------- |
| Aplicatia           | http://localhost:8000                          |
| Vite (HMR)          | http://localhost:5173                          |
| Mailpit (email-uri) | http://localhost:8025                          |
| MinIO console       | http://localhost:9001 (minioadmin / minioadmin) |
| MySQL               | localhost:3306 (renteaza / secret)             |

Oprire: `docker compose down`. Stergere completa cu tot cu date:
`docker compose down -v`.

## Ce face fiecare serviciu

- **app** — `php artisan serve`. Tot ce e initializare (composer install, APP_KEY,
  `storage:link`, `migrate`, `db:seed`) ruleaza aici, in `docker/php/entrypoint.sh`.
- **queue** — `php artisan queue:work` (proiectul are `QUEUE_CONNECTION=database`
  si job-ul `TransferOwnerPayout`).
- **vite** — dev server cu HMR. Codul din `resources/` se reincarca automat.
- **mysql** — baza `renteaza`, date persistate in volumul `mysql-data`.
- **minio** + **minio-init** — S3 local. Se creeaza bucket-urile
  `renteaza-private` (documente, KYC) si `renteaza-public` (poze masini, logo-uri),
  al doilea cu citire anonima ca sa se incarce imaginile in browser.
- **mailpit** — prinde toate email-urile, nu pleaca nimic in exterior.

## Comenzi uzuale

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker
docker compose exec app php artisan test
docker compose exec app composer require pachet/nou
docker compose exec vite npm install pachet-nou
docker compose logs -f app
```

## Chei care trebuie completate manual

Aplicatia porneste si fara ele, dar functionalitatile respective nu merg.
Le pui in `.env` si dai `docker compose restart app queue vite`:

- `VITE_GOOGLE_MAPS_API_KEY` — fara ea, hartile din pagini raman goale.
- `STRIPE_KEY` / `STRIPE_SECRET` — chei de test din dashboard-ul Stripe, pentru plati.
- `WISE_API_TOKEN` / `WISE_PROFILE_ID` — payout-uri catre proprietari (sandbox).
- `VONAGE_KEY` / `VONAGE_SECRET` — SMS-uri.

## Fisiere

- `docker-compose.yml` — serviciile.
- `docker/php/Dockerfile` — PHP 8.3 CLI + extensiile cerute de proiect
  (`pdo_mysql`, `gd`, `zip`, `intl`, `bcmath`, `exif`, `pcntl`, `opcache`) + Composer.
- `docker/php/php.ini` — limite de upload 100M (poze masini, import Excel).
- `docker/php/entrypoint.sh` — initializarea.
- `.env.docker` — sablonul de mediu pentru containere; `.env` a fost generat din el,
  cu `APP_KEY` deja completat.

## Migrare adaugata

`database/migrations/2025_09_07_012000_rename_checkin_photos_to_inspection_photos.php`
a fost adaugata pentru ca migrarile sa treaca pe o baza de date curata: migrarea
`..._010523_rename_checkin_to_inspection_submissions` redenumea doar tabela de
submisii, nu si `checkin_photos`, desi modelul `CheckinPhoto` si migrarea
`..._014021_add_type_to_inspection_photos_table` folosesc `inspection_photos`.
Migrarea e protejata cu `Schema::hasTable`, deci nu strica bazele existente.

## Podman (masina asta are podman, nu docker)

Podman merge cu acelasi fisier, dar are nevoie de un provider de compose:

```bash
sudo dnf install podman-compose
podman-compose up --build
```

sau, pentru compose-ul original v2 peste podman:

```bash
sudo dnf install docker-compose
systemctl --user enable --now podman.socket
podman compose up --build
```

In podman rootless, procesele din container ruleaza ca `root` in container, adica
exact ca utilizatorul tau pe host — deci `vendor/` si `node_modules/` generate in
container raman ale tale, editabile normal. Cu Docker clasic aceleasi fisiere apar
ca `root:root` pe host; daca te deranjeaza:
`sudo chown -R $USER:$USER vendor node_modules storage bootstrap/cache`.
