# Elden Ring Community Website

Een community-platform voor Elden Ring spelers, gebouwd met Laravel 11, Inertia.js en Tailwind CSS. Het project combineert een publiek nieuwsoverzicht, een boss-database, een FAQ-systeem en een community-laag (comments, favorieten) met een volledig admin-paneel.

## Features

### Publiek
- Nieuwsoverzicht met detailpagina's en authenticated comments
- Boss-database met moeilijkheidsgraad-indicatoren
- FAQ-systeem georganiseerd per categorie
- Contact-formulier
- Authenticatie met registratie, login, password reset en email verification

### Authenticated users
- Profielpagina's
- Comments achterlaten op nieuwsartikelen
- Bosses toevoegen aan een persoonlijke favorietenlijst

### Admin
- Volledig admin-paneel met CRUD-operaties op users, news, bosses, FAQ-categorieën en FAQ-items
- Toegangscontrole via een dedicated `IsAdmin` middleware

## Technische stack

- PHP 8.2 of hoger
- Laravel 11
- Inertia.js
- Tailwind CSS
- SQLite (lokaal) of MySQL (productie)
- Node.js en npm voor asset-builds

## Lokale installatie

### 1. Clone de repository

```bash
git clone https://github.com/big-dawg-bit/eldenring-community-site.git
cd eldenring-community-site
```

### 2. Installeer dependencies

```bash
composer install
npm install
```

### 3. Environment configuratie

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database initialiseren

Voor lokale ontwikkeling staat SQLite standaard ingesteld in `.env.example`. Maak het database-bestand aan:

```bash
touch database/database.sqlite
```

Voer migraties en seeders uit:

```bash
php artisan migrate:fresh --seed
```

De seeders maken een admin-account, enkele test-users, FAQ-categorieën, FAQ-items, nieuwsartikelen en boss-data aan. De admin-credentials worden gegenereerd door `AdminUserSeeder` op basis van de waarden gedefinieerd in dat bestand. Open `database/seeders/AdminUserSeeder.php` om te zien welke credentials lokaal toegekend worden.

### 5. Storage link

```bash
php artisan storage:link
```

### 6. Start de development server

```bash
php artisan serve
```

Open een tweede terminal voor de asset-watcher:

```bash
npm run dev
```

De applicatie is bereikbaar op `http://localhost:8000`.

## Productie deployment

Productie-deployment vereist andere instellingen dan lokale ontwikkeling. Een aparte template hiervoor bevindt zich in `.env.production.example`. De belangrijkste verschillen worden hieronder opgesomd.

### Verplichte stappen voor productie

1. Kopieer `.env.production.example` naar `.env` op de productie-server
2. Genereer een nieuwe `APP_KEY` met `php artisan key:generate`
3. Stel `APP_DEBUG=false` en `APP_ENV=production` in
4. Configureer een productie-database (MySQL aanbevolen, geen SQLite)
5. Configureer een echte SMTP-mailserver
6. Stel `SESSION_SECURE_COOKIE=true` in (vereist HTTPS)
7. Voer migraties uit met `php artisan migrate --force`
8. Build de assets met `npm run build`
9. Cache de configuratie:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

10. Wijzig de admin-credentials onmiddellijk na de eerste login

### Productie security checklist

- HTTPS afgedwongen op alle routes (gebeurt automatisch via `AppServiceProvider`)
- Security headers actief op alle responses (geleverd door `SecurityHeaders` middleware)
- Rate limiting actief op login, register, contact, comments en password reset
- Debug-routes verwijderd uit `routes/web.php`
- Geen hardcoded credentials in repository of `.env.example`
- Sessie-cookies versleuteld, secure en met `SameSite=strict`

Een uitgebreid overzicht van security-maatregelen staat in `SECURITY.md`.

## Project structuur

### Models
User, News, Boss, Faq, FaqCategory, Comment

### Relaties
**One-to-many:** User naar News, User naar Comments, News naar Comments, FaqCategory naar Faqs.
**Many-to-many:** User naar Boss via een pivot-tabel `boss_user` voor favorieten.

### Middleware
`IsAdmin` controleert admin-rechten op admin-routes. `SecurityHeaders` voegt HTTP security headers toe aan elke response.

## Bronvermelding

Laravel Framework: https://laravel.com  
Tailwind CSS: https://tailwindcss.com  
Inertia.js: https://inertiajs.com  
Google Fonts (Cinzel): https://fonts.google.com  
Elden Ring content, boss-afbeeldingen en game UI inspiratie: FromSoftware / Bandai Namco Entertainment  
Aanvullende ondersteuning bij ontwikkeling: claude.ai en gemini.google.com

## Ontwikkelaar

Arnaud Raspe  
Erasmushogeschool Brussel  
Backend Web Development, 2024-2025

## Licentie

Dit project staat onder MIT-licentie. Zie `LICENSE` voor de volledige tekst. Elden Ring is een geregistreerd handelsmerk van FromSoftware en Bandai Namco Entertainment. Game-content wordt enkel gebruikt voor educatieve doeleinden.
