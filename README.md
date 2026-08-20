# BBIP Peak Performing Platform Landing Page

A production-ready modern landing page built with Laravel 12, Blade templates, Tailwind CSS, Alpine.js, and Docker.

## Tech Stack

- **Framework**: Laravel 12
- **Frontend**: Blade Templates + Alpine.js
- **Styling**: Tailwind CSS via Vite
- **Asset Building**: Vite
- **Runtime**: PHP 8.4 FPM
- **Web Server**: Nginx
- **Containerization**: Docker & Docker Compose
- **Node**: 22 Alpine (for frontend build)

## Features

- Clean Blade component architecture
- Responsive design (mobile-first)
- Server-rendered HTML
- Lightweight Alpine.js for interactions
- Optimized Vite asset pipeline
- Production-ready Docker setup
- Multi-stage Docker build

## Project Structure

```
resources/
├── css/
│   └── app.css              # Tailwind CSS
├── js/
│   └── app.js               # Alpine.js entry point
└── views/
    ├── layouts/
    │   └── app.blade.php    # Main layout
    ├── components/
    │   ├── navbar.blade.php
    │   ├── hero.blade.php
    │   ├── features.blade.php
    │   ├── stats.blade.php
    │   ├── testimonials.blade.php
    │   ├── faq.blade.php
    │   ├── cta.blade.php
    │   └── footer.blade.php
    └── welcome.blade.php    # Landing page

docker/
├── nginx/
│   └── default.conf        # Nginx configuration
└── supervisor/
    └── supervisord.conf    # Service management

config/                      # Laravel configuration
app/                        # Application code
public/
└── index.php               # Laravel entry point
```

## Getting Started

### Prerequisites

- Docker
- Docker Compose

### Local Development

```bash
# Build the Docker image
docker compose build

# Start the containers
docker compose up -d

# Verify services are running
docker compose ps

# View logs
docker compose logs -f app
```

Access the site at `http://localhost`

### Stopping the Application

```bash
docker compose down
```

## Development Commands

```bash
# Build frontend assets (inside container)
docker compose exec app npm run build

# Watch frontend during development (if needed)
docker compose exec app npm run dev
```

## Production Deployment

The Docker image is production-ready with:

- Multi-stage build (Node → Composer → PHP-FPM)
- Optimized PHP with OPcache
- Nginx with security headers
- Proper file permissions
- No development dependencies in final image
- Volume mounts only for essential directories (storage, cache)

### Environment Variables

Configure via `.env` file or Docker Compose environment section:

```env
APP_NAME="BBIP Landing"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=http://example.com
CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=cookie
```

## Architecture Notes

### Docker Architecture

```
Nginx (Port 80)
    ↓
PHP-FPM (localhost:9000)
    ↓
Laravel Application
```

Services are managed by Supervisor for reliability.

### Frontend Build

The Vite build process:
1. **Development**: Assets served through Vite dev server
2. **Production**: Assets compiled to `public/build/` with cache-busting hashes

Critical: The Docker build copies compiled assets into the final image. Runtime volumes do NOT overwrite `public/build`.

### Asset Caching

Static assets in `public/build/` are served with 1-year cache headers due to Vite's content hashing.

## Security

- Security headers configured in Nginx
- Hidden files (`.htaccess`, `.env`) are blocked
- Sensitive directories (`storage`, `bootstrap`) are inaccessible
- CSRF protection via middleware
- Cookie encryption enabled

## Performance

- Server-side rendering (Blade)
- Minimal JavaScript (Alpine.js only)
- Vite production asset hashing
- OPcache enabled
- Gzip compression via Nginx

## Troubleshooting

### Port Already in Use

If port 80 is busy, modify `docker-compose.yml`:
```yaml
ports:
  - "8080:80"  # Access at localhost:8080
```

### Permission Issues

The Dockerfile sets proper ownership:
```bash
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache
```

### View Cache Issues

Clear Laravel cache:
```bash
docker compose exec app php artisan view:clear
docker compose exec app php artisan config:clear
```

## File Permissions

- `storage/`: writable by PHP process
- `bootstrap/cache/`: writable by PHP process
- `public/build/`: owned by www-data (copied during build)

## License

MIT License. See LICENSE file for details.

## Support

For issues or questions, please check the documentation or contact support.
