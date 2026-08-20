# Docker Setup for BBIP Landing

## Quick Start

### Using Docker Compose for Development

1. **Set up environment variables:**

   ```bash
   cp .env.example .env
   ```

2. **Start the containers:**

   ```bash
   docker-compose up -d
   ```

3. **Install PHP dependencies:**

   ```bash
   docker-compose exec app composer install
   ```

4. **Install Node dependencies:**

   ```bash
   docker-compose exec vite npm install
   ```

5. **Generate app key:**

   ```bash
   docker-compose exec app php artisan key:generate
   ```

### Access the Application

- **Laravel App:** http://localhost:8000
- **Vite Dev Server:** http://localhost:5173 (HMR)

## Hot Module Replacement (HMR) with Vite

Vite automatically watches for changes in your CSS and JavaScript files. When you make changes:

1. **CSS/JS Changes** - Automatically refresh in the browser (Fast Refresh)
2. **Blade Template Changes** - Page refresh required (handled by Vite)

The Vite dev server runs in a separate container and watches file changes. Just save your file and refresh the browser to see updates!

## Services

### PHP App (app)

- PHP 8.2 with necessary extensions
- Runs Laravel development server
- Port: 8000

### Vite Dev Server (vite)

- Node 18 with Vite
- Watches for file changes
- Hot Module Replacement (HMR) enabled
- Port: 5173

## Development Workflow

### Making Changes with Hot Reload

1. **CSS Changes** (resources/css/app.css):
   - Edit the file
   - Vite automatically detects changes
   - Browser auto-refreshes

2. **JavaScript Changes** (resources/js/app.js or components):
   - Edit the file
   - Vite HMR updates in real-time
   - Most changes don't require full refresh

3. **Blade Templates** (resources/views/):
   - Edit the file
   - Manual browser refresh needed (Vite handles this)
   - Use Tailwind utilities normally

## Common Commands

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# View logs
docker-compose logs -f app      # Laravel app
docker-compose logs -f vite     # Vite dev server

# Run artisan commands
docker-compose exec app php artisan <command>

# Rebuild containers
docker-compose build --no-cache && docker-compose up -d

# Install/update npm packages
docker-compose exec vite npm install
docker-compose exec vite npm install <package-name>

# Install/update composer packages
docker-compose exec app composer install
docker-compose exec app composer require <package-name>
```

## Environment Variables

Edit `.env` to customize:

- `APP_ENV` - Application environment (local/production)
- `APP_DEBUG` - Debug mode (true/false)
- `APP_URL` - Application URL

## Troubleshooting

**Containers not starting?**

```bash
docker-compose logs
```

**Vite not detecting changes?**

```bash
# Restart the Vite container
docker-compose restart vite

# Check Vite logs
docker-compose logs -f vite
```

**HMR not connecting?**

- Make sure port 5173 is accessible
- Check browser console for errors
- Restart Vite: `docker-compose restart vite`
- Verify VITE_HOST=localhost in docker-compose.yml

**Node modules causing issues?**

```bash
# Rebuild node_modules
docker-compose down
docker-compose up -d
docker-compose exec vite npm install
```

**npm install not finding packages?**

```bash
docker-compose exec vite npm cache clean --force
docker-compose exec vite npm install
```

**Styles/JS not appearing after changes?**

```bash
# Clear Laravel cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear

# Restart Vite
docker-compose restart vite
```

**Port already in use?**

```bash
# Change port in docker-compose.yml
# For example, change "8000:8000" to "8001:8000"
docker-compose down
docker-compose up -d
```
