# Guide de déploiement Les Mets Chics

## 1. Configuration Supabase (PostgreSQL)

### Backend .env
```env
DB_CONNECTION=pgsql
DB_HOST=db.xxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=ton_password_supabase
```

### Installation driver PostgreSQL
```bash
composer require doctrine/dbal
```

## 2. Configuration Render (Backend)

### render.yaml
```yaml
services:
  - type: web
    name: les-mets-chics-api
    env: php
    buildCommand: |
      composer install --optimize-autoloader --no-dev
      php artisan config:cache
      php artisan route:cache
      php artisan view:cache
      php artisan migrate --force
    startCommand: |
      php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: APP_URL
        value: https://ton-app.onrender.com
      - key: DB_CONNECTION
        value: pgsql
      - key: DB_HOST
        fromDatabase:
          name: supabase
          property: host
      - key: DB_DATABASE
        fromDatabase:
          name: supabase
          property: database
      - key: DB_USERNAME
        fromDatabase:
          name: supabase
          property: user
      - key: DB_PASSWORD
        fromDatabase:
          name: supabase
          property: password
```

### Variables d'environnement Render
```
APP_KEY=base64:ton_app_key_generee
SANCTUM_STATEFUL_DOMAINS=ton-frontend.vercel.app
SESSION_DOMAIN=.onrender.com
```

## 3. Configuration Vercel (Frontend)

### vercel.json
```json
{
  "builds": [
    {
      "src": "nuxt.config.ts",
      "use": "@nuxtjs/vercel-builder"
    }
  ]
}
```

### Variables d'environnement Vercel
```
NUXT_PUBLIC_API_BASE=https://ton-backend.onrender.com/api
```

## 4. CORS Configuration

### Backend config/cors.php
```php
'allowed_origins' => [
    'https://ton-frontend.vercel.app',
    'https://*.vercel.app'
],
'allowed_origins_patterns' => [
    '/^https:\/\/.*\.vercel\.app$/'
],
```

## 5. Storage Configuration

### Backend config/filesystems.php
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### Commandes de déploiement
```bash
# Backend
php artisan storage:link
php artisan config:cache
php artisan migrate --force

# Frontend
npm run build
```