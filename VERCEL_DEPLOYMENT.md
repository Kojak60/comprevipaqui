# Vercel Deployment Guide for Laravel E-Commerce

## Project Structure
- `index.php` - Entry point (at root)
- `core/` - Laravel application
- `assets/` - Static assets (CSS, JS, images)
- `vercel.json` - Vercel configuration

## Prerequisites
1. Vercel account
2. Git repository (GitHub, GitLab, or Bitbucket)
3. External database (MySQL/PostgreSQL recommended for production)

## Deployment Steps

### 1. Push to Git Repository
```bash
git init
git add .
git commit -m "Initial commit for Vercel deployment"
git remote add origin <your-repo-url>
git push -u origin main
```

### 2. Configure Vercel Project
1. Go to [Vercel Dashboard](https://vercel.com/dashboard)
2. Click "Add New..." → "Project"
3. Import your Git repository
4. Vercel will auto-detect the `vercel.json` configuration

### 3. Set Environment Variables
In Vercel Project Settings → Environment Variables, add:

**Required:**
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key
APP_URL=https://your-project.vercel.app
LOG_CHANNEL=stderr
CACHE_DRIVER=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

**Database (choose one):**

*Option A: External MySQL (Recommended)*
```
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

*Option B: External PostgreSQL*
```
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

*Option C: SQLite (Not recommended for production - data won't persist)*
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

**Mail (configure as needed):**
```
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Your Store"
```

### 4. Generate APP_KEY
Run locally and copy the output:
```bash
cd core
php artisan key:generate --show
```
Add the output as `APP_KEY` in Vercel environment variables.

### 5. Run Migrations
Since Vercel is serverless, run migrations against your external database:
```bash
# Locally with production .env
cd core
php artisan migrate --force
```

Or use a CI/CD pipeline to run migrations on deploy.

### 6. Deploy
Click "Deploy" in Vercel. The build will:
1. Install PHP dependencies (`composer install`)
2. Create the deployment

## Important Notes

### Static Assets
Assets in `assets/` folder are served automatically by Vercel as static files.

### Database
- **SQLite**: Not recommended for production on Vercel (data lost on cold starts)
- **External DB**: Use PlanetScale, Neon, Supabase, Railway, or similar services

### File Storage
- Local storage won't persist on Vercel
- Configure S3 or similar for file uploads in `config/filesystems.php`

### Queue Workers
- `QUEUE_CONNECTION=sync` for serverless
- For async jobs, use external queue service (Redis + Laravel Horizon on separate server)

### Cron Jobs
Use Vercel Cron Jobs or external scheduler for scheduled tasks.

## Troubleshooting

### Build Fails
- Check build logs in Vercel dashboard
- Ensure all PHP extensions are available (Vercel PHP 8.3 includes common extensions)

### 500 Errors
- Check `APP_DEBUG=false` in production
- Check Vercel Function logs
- Ensure `APP_KEY` is set correctly

### Assets Not Loading
- Verify `APP_URL` matches your Vercel domain
- Check browser console for 404 errors on asset URLs

## Local Development
```bash
cd core
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Useful Commands
```bash
# Clear caches
php artisan optimize:clear

# Generate optimized autoloader
composer dump-autoload -o

# Run tests
php artisan test
```