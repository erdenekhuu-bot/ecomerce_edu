# Laravel Docker Production Setup

## Quick Start (Windows)

### Prerequisites

- Docker Desktop installed and running
- Your Laravel project with TypeScript frontend

### Setup Steps

1. **Copy files to your Laravel project root:**

    ```
    Dockerfile
    docker-compose.yml
    nginx.conf
    .dockerignore
    ```

2. **Create SSL directory (for HTTPS later):**

    ```powershell
    mkdir ssl
    ```

3. **Update docker-compose.yml environment:**
    - Set `APP_KEY` from your `.env`
    - Update `DB_PASSWORD` to something secure

4. **Build and start:**

    ```powershell
    docker compose up -d
    ```

5. **Run migrations:**

    ```powershell
    docker compose exec app php artisan migrate
    ```

6. **Access your app:**
    - Frontend: http://localhost
    - API: http://localhost/api

## Architecture

```
Windows PC
├── docker compose
│   ├── PostgreSQL (5432)
│   ├── PHP-FPM (9000, internal)
│   └── Nginx (80/443)
```

**Data Flow:**

1. Browser → Nginx (port 80)
2. Nginx → PHP-FPM (localhost:9000, no exposure)
3. PHP-FPM → PostgreSQL (internal network)

## Common Commands

```powershell
# View logs
docker compose logs -f app
docker compose logs -f db
docker compose logs -f web

# Shell access
docker compose exec app bash
docker compose exec db psql -U laravel -d laravel

# Run artisan commands
docker compose exec app php artisan tinker
docker compose exec app php artisan queue:work

# Rebuild after code changes
docker compose build --no-cache
docker compose up -d

# Stop everything
docker compose down

# Clean up (volumes + containers)
docker compose down -v
```

## Environment Configuration

### .env variables to set:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel_password

FRONTEND_URL=http://localhost
```

## Production Notes

### On a real server:

1. Use secrets management (Docker Secrets or environment variables from secure source)
2. Enable HTTPS: Uncomment HTTPS block in `nginx.conf`, add certificates to `ssl/`
3. Use `docker compose -f docker-compose.prod.yml` with different config
4. Set `APP_DEBUG=false` and `APP_ENV=production`
5. Use container orchestration: Docker Swarm or Kubernetes

### For local testing:

- This setup is perfect as-is
- PostgreSQL data persists in `postgres_data` volume
- App code changes require `docker compose build` + restart

## Troubleshooting

**Port 80 already in use:**

```powershell
docker compose.yml: Change `80:80` to `8080:80`
# Then access at http://localhost:8080
```

**Permission denied on storage:**

```powershell
docker compose exec app chmod -R 775 storage bootstrap/cache
```

**Database connection failed:**

```powershell
docker compose exec db psql -U laravel -d laravel -c "SELECT 1;"
```

**PHP extensions missing:**
Edit Dockerfile and add to `docker-php-ext-install` section, then rebuild.

## Multi-Stage Build Explanation

1. **Frontend stage (Node 18):** Builds TypeScript → JavaScript
2. **Backend stage (PHP 8.2):** Installs PHP + extensions, copies compiled frontend assets

This ensures:

- No Node.js in production image (smaller, faster)
- TypeScript compiles during build
- Single image for deployment
