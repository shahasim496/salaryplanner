# 🚀 Deployment Guide for InfinityFree Hosting

## Prerequisites

1. **InfinityFree Account**: Sign up at https://www.infinityfree.net
2. **Domain**: You'll get a free subdomain (e.g., `yoursite.rf.gd`) or use your own domain
3. **Database**: SQLite database (no setup required - file-based)

## Step 1: Prepare Your Application

### 1.1 Install Dependencies Locally

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build assets
npm install
npm run build
```

### 1.2 Generate APP_KEY

**First, generate your application key locally:**

```bash
# In your local project directory
php artisan key:generate --show
```

This will output something like: `base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

Copy this entire key (including the `base64:` prefix) - you'll need it for your production `.env` file.

### 1.3 Create Production .env File

Create a `.env` file with your InfinityFree settings:

```env
APP_NAME="Salary Planner"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://salaryplanner.infinityfree.me

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

**Important**: Replace:
- `YOUR_APP_KEY_HERE` - Paste the key you generated in Step 1.2 (the full `base64:...` string)
- `salaryplanner.infinityfree.me` - Your InfinityFree domain (update if different)
- `/absolute/path/to/database/database.sqlite` - Absolute path to your SQLite database file
  - You can use relative path: `database/database.sqlite` (Laravel will resolve it automatically)
  - Or use absolute path if you know it (check in InfinityFree File Manager)
  - For Option B, the path would be relative to `htdocs/salary-planner/`

## Step 2: Upload Files to InfinityFree

### 2.1 File Structure

InfinityFree uses `htdocs` or `public_html` as the web root. You have two options:

#### Option A: Standard Laravel Structure (Recommended)
```
htdocs/
├── .htaccess (redirect to public)
├── public/ (all Laravel files here)
│   ├── index.php
│   ├── .htaccess
│   └── ... (all other Laravel files)
```

#### Option B: Subdomain Structure
```
htdocs/
├── .htaccess (redirects to salary-planner/public/)
└── salary-planner/ (all Laravel files)
    ├── public/
    │   ├── index.php
    │   ├── .htaccess
    │   └── ...
    └── ... (all other Laravel files)
```

**Your InfinityFree Structure:**
- Web Root: `htdocs/`
- Domain: `salaryplanner.infinityfree.me`
- Upload all Laravel files to: `htdocs/salary-planner/`

### 2.2 Files to Upload

Upload ALL files EXCEPT:
- `.env` (create on server)
- `node_modules/` (not needed)
- `.git/` (not needed)
- `tests/` (optional)

**Note**: If you have a local `database/database.sqlite` file with data, you can upload it. Otherwise, it will be created automatically when you run migrations.

### 2.3 Create .htaccess in Root

#### For Option A (Standard Laravel Structure)

Create `.htaccess` in `htdocs/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### For Option B (Subdomain Structure)

Create `.htaccess` in `htdocs/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ salary-planner/public/$1 [L]
</IfModule>
```

This redirects all requests from the root to `salary-planner/public/` where your Laravel application is located.

## Step 3: Configure InfinityFree

### 3.1 Database Setup

SQLite doesn't require any database server setup! The database file will be created automatically when you run migrations.

**Important**: Make sure the `database/` directory exists and is writable (permissions 755 or 777).

### 3.2 Update .env File on Server

1. Upload your `.env` file via FTP/File Manager to `htdocs/salary-planner/` (for Option B)
2. Update `DB_DATABASE` path:
   - **Option A**: `/home/username/htdocs/database/database.sqlite` or `database/database.sqlite`
   - **Option B**: `/home/username/htdocs/salary-planner/database/database.sqlite` or `database/database.sqlite` (relative to salary-planner folder)
   - You can find the absolute path in InfinityFree File Manager
   - Or use relative path: `database/database.sqlite` (Laravel will resolve it automatically)
3. Set `APP_URL` to your domain: `https://salaryplanner.infinityfree.me`
4. Set `APP_DEBUG=false` for production
5. Ensure `APP_KEY` is set (generate locally with `php artisan key:generate` and paste the value)

### 3.3 Set File Permissions

Set these permissions via File Manager or FTP:
- `storage/` → 755 (or 777 if needed)
- `storage/framework/` → 755
- `storage/framework/cache/` → 755
- `storage/framework/sessions/` → 755
- `storage/framework/views/` → 755
- `storage/logs/` → 755
- `bootstrap/cache/` → 755
- `database/` → 755 (or 777 if needed - required for SQLite)
- `database/database.sqlite` → 666 (if file exists, needs write permissions)

## Step 4: Run Migrations

### Option 1: Via InfinityFree PHP Script

Create `migrate.php` in:
- **Option A**: `htdocs/public/migrate.php`
- **Option B**: `htdocs/salary-planner/public/migrate.php`

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create database file if it doesn't exist
$dbPath = __DIR__.'/../database/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
    chmod($dbPath, 0666);
}

Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
```

Then visit: `https://salaryplanner.infinityfree.me/migrate.php`

**⚠️ DELETE THIS FILE AFTER MIGRATION!**

### Option 2: Via InfinityFree Terminal (if available)

Some InfinityFree plans offer terminal access. If available:

```bash
# For Option A
cd htdocs/public
php artisan migrate --force

# For Option B
cd htdocs/salary-planner/public
php artisan migrate --force
```

## Step 5: Optimize Laravel

After deployment, optimize Laravel:

```php
<?php
// Create optimize.php in:
// Option A: htdocs/public/optimize.php
// Option B: htdocs/salary-planner/public/optimize.php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

Artisan::call('config:cache');
Artisan::call('route:cache');
Artisan::call('view:cache');
echo "Optimization complete!";
```

Visit: `https://salaryplanner.infinityfree.me/optimize.php`

**⚠️ DELETE THIS FILE AFTER OPTIMIZATION!**

## Step 6: Update Mobile App API URL

Update `mobile-app/src/config/api.js`:

```javascript
const API_BASE_URL = __DEV__ 
  ? 'http://192.168.1.80:8000/api'  // Local development
  : 'https://salaryplanner.infinityfree.me/api'; // Production - your InfinityFree domain
```

## Step 7: Test Your Deployment

1. Visit your domain: `https://salaryplanner.infinityfree.me`
2. Test registration/login
3. Test all CRUD operations
4. Check mobile app connection

## Common Issues & Solutions

### Issue 1: 500 Internal Server Error
- Check `.env` file exists and has correct values
- Check file permissions on `storage/` and `bootstrap/cache/`
- Check error logs in `storage/logs/laravel.log`

### Issue 2: Database Connection Error
- Verify `DB_CONNECTION=sqlite` in `.env`
- Check `DB_DATABASE` path is correct (absolute or relative path)
- Ensure `database/` directory exists and is writable (755 or 777)
- Ensure `database/database.sqlite` file exists and is writable (666)
- If database file doesn't exist, create it: `touch database/database.sqlite && chmod 666 database/database.sqlite`

### Issue 3: Assets Not Loading
- Run `npm run build` locally and upload `public/build/`
- Check `APP_URL` in `.env` matches your domain
- Clear browser cache

### Issue 4: Route Not Found
- Run `php artisan route:cache` (via script)
- Check `.htaccess` in `public/` folder
- Verify `APP_URL` is correct

### Issue 5: Permission Denied
- Set `storage/` and `bootstrap/cache/` to 755 or 777
- Check InfinityFree file permission settings

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong `APP_KEY` (generate with `php artisan key:generate`)
- [ ] Set proper file permissions on `database/database.sqlite` (666)
- [ ] Delete `migrate.php` and `optimize.php` after use
- [ ] Don't commit `.env` file
- [ ] Don't commit `database/database.sqlite` if it contains sensitive data
- [ ] Enable HTTPS (InfinityFree provides free SSL)

## Maintenance

### Clear Cache (if needed)
Create `clear-cache.php`:

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');
echo "Cache cleared!";
```

## Support

If you encounter issues:
1. Check `storage/logs/laravel.log`
2. Check InfinityFree error logs in control panel
3. Verify all file permissions (especially `database/` and `database/database.sqlite`)
4. Ensure database file exists and is writable
5. Verify `DB_DATABASE` path in `.env` is correct

Good luck with your deployment! 🚀

