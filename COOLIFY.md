# Deploy RENTeaza pe Coolify

Stack: `app` (nginx + php-fpm), `queue`, `mariadb` — trei containere, un singur
resource Coolify, fara AWS si fara object storage.

Fisierele (poze masini, logo-uri, documente) stau pe discul serverului, in
volumul `app-storage`. Disk-urile Laravel se numesc in continuare `aws-public`
si `aws-private`, dar folosesc driverul `local` — vezi `config/filesystems.php`.

Fisierul de deploy e `docker-compose.prod.yml`. `docker-compose.yml` ramane
pentru dezvoltare locala (vezi `DOCKER.md`) si **nu** se foloseste in productie:
acolo codul e montat de pe disc si ruleaza Vite dev server.

---

## 0. Proiectul trebuie sa fie intr-un repo Git

Coolify face deploy dintr-un repository, iar acesta inca nu e unul:

```bash
git init
git add .
git commit -m "Setup Docker si deploy Coolify"
git remote add origin git@github.com:<user>/renteaza.git
git push -u origin main
```

`.gitignore` exclude deja `.env`, `vendor/` si `node_modules/`. Verifica inainte
de push ca nu urci chei reale.

---

## 1. Creeaza resource-ul in Coolify

**New Resource → Docker Compose**, conecteaza repo-ul, apoi:

| Setare                  | Valoare                     |
| ----------------------- | --------------------------- |
| Base Directory          | `/`                         |
| Docker Compose Location | `/docker-compose.prod.yml`  |

## 2. Stabileste domeniul

Un singur domeniu, pentru serviciul `app`. Coolify genereaza automat unul pentru
`SERVICE_FQDN_APP_80`; il inlocuiesti cu al tau din UI (Configuration → Domains).
Domeniul **nu se scrie in docker-compose.prod.yml**.

`mariadb` nu are `ports:` expuse, deci ramane doar pe reteaua interna.

### Daca rulezi fara certificat (http simplu)

Cand aplicatia nu e publica, Let's Encrypt nu poate emite certificat (validarea
cere portul 80 accesibil din internet), deci ramai pe `http://`. In cazul asta
adauga in Coolify:

```
SESSION_SECURE_COOKIE=false
```

Altfel browserul refuza sa trimita cookie-ul de sesiune pe http, iar login-ul te
arunca la nesfarsit inapoi la formular, fara niciun mesaj de eroare.

## 3. Variabile de mediu

### Generate automat de Coolify — nu le completa

`SERVICE_PASSWORD_MYSQL` si `SERVICE_PASSWORD_MYSQLROOT` — parolele bazei de
date. Coolify le creeaza la primul deploy si le pastreaza.

### Obligatorii — le pui tu

| Variabila               | Valoare                                             |
| ----------------------- | --------------------------------------------------- |
| `APP_KEY`               | `base64:...` (vezi mai jos)                          |
| `APP_NAME`              | `RENTeaza`                                           |
| `AWS_PUBLIC_URL`        | `https://renteaza.ro/storage`                        |
| `GOOGLE_MAPS_API_KEY`   | cheia ta Google Maps                                 |
| `STRIPE_KEY`            | `pk_live_...`                                        |
| `STRIPE_SECRET`         | `sk_live_...`                                        |
| `MAIL_HOST`             | serverul tau SMTP                                    |
| `MAIL_PORT`             | `587`                                                |
| `MAIL_USERNAME`         |                                                      |
| `MAIL_PASSWORD`         |                                                      |
| `MAIL_FROM_ADDRESS`     | `no-reply@renteaza.ro`                               |
| `WISE_API_TOKEN`        | doar daca folosesti payout-uri                       |
| `WISE_PROFILE_ID`       | idem                                                 |
| `VONAGE_KEY` / `SECRET` | doar daca trimiti SMS                                |

`APP_KEY` il generezi o singura data si nu-l mai schimbi niciodata — daca il
schimbi, sesiunile si orice date criptate devin ilizibile:

```bash
docker compose exec app php artisan key:generate --show
```

### Doua dintre ele trebuie bifate ca **Build Variable**

```
AWS_PUBLIC_URL
GOOGLE_MAPS_API_KEY
```

Vite nu citeste variabile la runtime: `VITE_AWS_PUBLIC_URL` si
`VITE_GOOGLE_MAPS_API_KEY` se **compileaza in bundle-ul JavaScript** in timpul
build-ului. Daca nu sunt disponibile la build, in JS-ul livrat raman goale si
pozele masinilor apar ca `undefined/vehicles/...`, iar hartile nu se incarca.

De aceea `AWS_PUBLIC_URL` trebuie sa fie corect **inainte** de primul build.
E domeniul aplicatiei plus `/storage`. Daca schimbi ulterior domeniul,
**redeploy cu rebuild**, nu doar restart.

## 4. Primul deploy

Apasa Deploy. La pornire, containerul `app`:

1. asteapta MariaDB,
2. ruleaza `php artisan migrate --force`,
3. face `config:cache`, `view:cache`, `event:cache`,
4. porneste nginx + php-fpm.

`queue` porneste dupa ce `app` e healthy si nu ruleaza migrari (`RUN_MIGRATIONS=false`),
ca sa nu se calce pe picioare.

> `route:cache` **nu** se ruleaza: `routes/web.php` si `routes/user.php` definesc
> rute cu closure, iar Laravel nu le poate serializa. Daca vrei si acel cache,
> muta cele 5 closure-uri in controllere.

## 5. Seederele ruleaza automat

Containerul `app` ruleaza `php artisan db:seed --force` la **fiecare deploy**
(`RUN_SEEDERS=true`). Nu ai nimic de facut manual: rolurile `admin`, `user`,
`company-owner` si contul de administrator exista de la prima pornire.

```
admin@example.ro / test1234
```

Seederele sunt idempotente — verificat pe doua deploy-uri consecutive: 3 roluri,
un singur cont, un singur rand in `user_roles`.

Daca esueaza, aplicatia porneste oricum si eroarea apare in logurile Coolify.
Migrarile, in schimb, opresc deploy-ul daca esueaza — acolo integritatea datelor
conteaza mai mult.

### Consecinta de retinut

Parola de admin e **rescrisa la fiecare deploy** cu valoarea din
`AdminUserSeeder`. Daca o schimbi din interfata, urmatorul deploy o aduce inapoi
la `test1234`.

Ai doua optiuni cand vrei altceva:

- editezi constantele din `database/seeders/AdminUserSeeder.php` (parola noua se
  aplica la urmatorul deploy);
- setezi `RUN_SEEDERS=false` in Coolify, si atunci seederele nu mai ruleaza
  deloc — nu e nevoie de redeploy de cod, doar de restart.

---

## De ce MariaDB si nu MySQL

Imaginea oficiala `mysql:8` e construita pe Oracle Linux, cu glibc compilat
pentru `x86-64-v2`. Pe vCPU-uri fara SSE4.2 (modelul `qemu64`, frecvent la VPS-uri)
containerul moare instant cu:

```
Fatal glibc error: CPU does not support x86-64-v2
```

MariaDB e pe Debian/Ubuntu, compilat pentru x86-64 de baza, si porneste peste tot.
Schema nu foloseste nimic specific MySQL (fara `whereJsonContains`, fara SQL brut
in migrari), deci schimbarea e transparenta pentru aplicatie.

Daca vrei sa verifici ce suporta CPU-ul serverului:

```bash
/lib/x86_64-linux-gnu/ld-linux-x86-64.so.2 --help | grep -A3 "Subdirectories"
```

Rezolvarea la radacina ar fi ca furnizorul de VPS sa treaca modelul de CPU pe
`host-passthrough`, dar nu e mereu posibil — si nu e nevoie.

### Daca ai deja un volum de la incercarea cu MySQL

Sterge-l inainte de redeploy, altfel MariaDB gaseste un director de date pe
jumatate initializat si refuza sa porneasca. Volumul se numeste acum
`mariadb-data`, deci cel vechi (`mysql-data`) ramane orfan si poate fi sters din
Coolify → Storages.

## Ce se persista

| Volum          | Continut                                                    |
| -------------- | ----------------------------------------------------------- |
| `mariadb-data` | baza de date                                                |
| `app-storage`  | `storage/app` — toate fisierele urcate, publice si private   |

**Backup-uri:** Coolify nu face automat backup la o baza de date definita in
compose. Ai nevoie de doua lucruri programate: `mariadb-dump` pentru baza si o
copie a volumului `app-storage` pentru fisiere. Fara ele, un disc pierdut
inseamna rezervari, contracte si documente pierdute.

## Logurile

`LOG_CHANNEL=stderr`, deci totul apare direct in logurile Coolify ale
containerului — nu trebuie sa intri in container dupa `storage/logs/laravel.log`.

## Ce s-a verificat

Imaginea de productie a fost construita si rulata local cu podman, cu MariaDB
alaturi:

- `/up` raspunde `OK` (health-check-ul folosit de compose si de Coolify)
- pagina principala raspunde 200, cu asset-uri compilate de Vite (fara dev server)
- `VITE_AWS_PUBLIC_URL` chiar ajunge in `public/build/assets/app-*.js`
- upload pe disk-urile `aws-public` si `aws-private`, cu fisierul public
  servit corect prin `/storage/...` si cel privat inaccesibil din web
- cu header-ele `X-Forwarded-*` trimise de Traefik, Laravel genereaza corect
  `https://domeniu/build/...` (asta e rolul lui `trustProxies`, adaugat in
  `bootstrap/app.php`)
- imaginea nu contine `.env` si nici dependinte de dev

## Modificari facute in cod pentru deploy

- `bootstrap/app.php` — ruta `/up` pentru health-check si `trustProxies(at: '*')`.
  Fara `trustProxies`, in spatele proxy-ului Coolify Laravel ar genera URL-uri
  `http://` pe un site `https://` si s-ar strica asset-urile si redirect-urile.
  Ruta `/up` e declarata manual pentru ca argumentul `health:` al lui
  `withRouting()` e ignorat cand se paseaza un closure de rutare.
