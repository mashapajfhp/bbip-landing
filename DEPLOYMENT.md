# Deployment Verification Checklist

## Pre-Deployment

- [x] Laravel 12 application created
- [x] Blade components for all sections created
- [x] Tailwind CSS configured
- [x] Alpine.js integrated for interactions
- [x] Vite asset pipeline configured
- [x] Docker Dockerfile created with multi-stage build
- [x] Docker Compose configuration created
- [x] Nginx configuration for Laravel
- [x] PHP 8.4 FPM container setup
- [x] Environment configuration files

## Quick Start

```bash
# Build the Docker image
docker compose build

# Start the application
docker compose up -d

# Verify services are running
docker compose ps

# View logs
docker compose logs -f app
```

## Verification Steps

### 1. Container Status
```bash
docker compose ps
# Expected: bbip-landing container should be "Up"
```

### 2. HTTP Response
```bash
curl -s http://localhost/ | head -20
# Expected: HTML with BBIP Peak Performing Platform content
# Status: HTTP 200
```

### 3. Homepage Load
```bash
curl -s http://localhost/ | grep -o "Peak Performance\|BBIP\|features\|testimonials"
# Expected: Multiple matches showing page content
```

### 4. CSS Assets
```bash
curl -s http://localhost/ | grep "href=\"/build"
# Expected: Links to Vite-compiled CSS
```

### 5. JavaScript Assets
```bash
curl -s http://localhost/ | grep "src=\"/build"
# Expected: Links to Vite-compiled JavaScript
```

### 6. Mobile Navigation (Alpine.js)
```bash
curl -s http://localhost/ | grep "x-data\|@click"
# Expected: Alpine.js directives for mobile menu
```

### 7. FAQ Accordion
```bash
curl -s http://localhost/ | grep "faq-item"
# Expected: FAQ items with Alpine.js toggle functionality
```

### 8. Security Headers
```bash
curl -s -D - http://localhost/ | grep -E "X-Frame-Options|X-Content-Type-Options|X-XSS-Protection"
# Expected:
# X-Frame-Options: SAMEORIGIN
# X-Content-Type-Options: nosniff
# X-XSS-Protection: 1; mode=block
```

### 9. No PHP Errors
```bash
curl -s http://localhost/ | grep -i "fatal\|error\|warning\|deprecated"
# Expected: No output (no errors in HTML)
```

### 10. Vite Assets Available
```bash
curl -s http://localhost/build/ 2>&1 | head -10
# Expected: Either directory listing or proper Vite asset response
```

## Production Checklist

- [ ] `.env` file configured for production
- [ ] APP_DEBUG set to false
- [ ] APP_KEY generated and set
- [ ] HTTPS configured (SSL certificate)
- [ ] Database migrations run (if needed)
- [ ] Storage directories have proper permissions
- [ ] Log rotation configured
- [ ] Supervisor running for service management
- [ ] PHP OPcache enabled
- [ ] Nginx caching headers optimal

## Rollback Procedure

```bash
# Stop containers
docker compose down

# Remove image if needed
docker image rm bbip-landing-app

# Restore from previous image tag if available
docker pull bbip-landing-app:previous-version
docker tag bbip-landing-app:previous-version bbip-landing-app:latest

# Restart
docker compose up -d
```

## Monitoring

### Real-time Logs
```bash
docker compose logs -f app
```

### Container Stats
```bash
docker stats bbip-landing
```

### Check Port
```bash
netstat -tuln | grep 80
# or
lsof -i :80
```

## Performance Optimization

1. **Assets**: Vite automatically generates cache-busting filenames
2. **PHP**: OPcache enabled in Dockerfile
3. **Nginx**: Gzip compression and proper caching headers configured
4. **Database**: Not needed for static landing page
5. **Sessions**: Cookie-based (array driver for stateless operation)

## Troubleshooting

### Container won't start
```bash
docker compose logs app
# Check for PHP/Nginx errors
```

### Port 80 already in use
```bash
# Modify docker-compose.yml to use different port
# Or stop other services:
sudo lsof -i :80 | grep LISTEN
```

### Permissions error
```bash
# Check file ownership
docker compose exec app ls -la storage/
# Should be owned by www-data
```

### View not found
```bash
# Clear view cache
docker compose exec app php artisan view:clear
docker compose exec app php artisan config:clear
```
