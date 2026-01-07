# ShopEase - Railway Deployment (FREE - No Credit Card)

## Why Railway?
- **No credit card required** for free tier
- $5 free credit/month
- Easy PostgreSQL setup
- Auto-deploy from GitHub

## Deployment Steps

### Step 1: Create Railway Account
1. Go to https://railway.app
2. Sign up with GitHub (recommended)

### Step 2: Create New Project
1. Click **"New Project"**
2. Select **"Deploy from GitHub repo"**
3. Connect your repository

### Step 3: Add PostgreSQL Database
1. In your project, click **"+ New"**
2. Select **"Database"** → **"PostgreSQL"**
3. Wait for it to provision

### Step 4: Configure Environment Variables
Click on your web service → **Variables** tab → Add these:

```
APP_NAME=ShopEase
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxxxx (generate locally with: php artisan key:generate --show)
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}
LOG_CHANNEL=stderr
DB_CONNECTION=pgsql
DATABASE_URL=${{Postgres.DATABASE_URL}}
SESSION_DRIVER=cookie
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

**Note:** `${{Postgres.DATABASE_URL}}` auto-links to your PostgreSQL service.

### Step 5: Deploy
Railway auto-deploys when you push to GitHub. First deploy takes ~5-10 minutes.

### Step 6: Get Your URL
After deploy, click **"Settings"** → **"Generate Domain"** to get your public URL.

## Post-Deployment

### Run Seeders (Optional)
1. Go to your service → **"Settings"** → **"Railway Shell"**
2. Run: `php artisan db:seed`

### Create Admin User
```bash
php artisan tinker
```
```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

## Free Tier Limits
- $5 credit/month (~500 hours)
- 512MB RAM
- Shared CPU
- Auto-sleep after inactivity (wakes on request)

## Troubleshooting

### Build Fails
- Check build logs in Railway dashboard
- Ensure Dockerfile is valid

### Database Connection Issues
- Verify `DATABASE_URL` variable is linked correctly
- Check PostgreSQL service is running

### 500 Errors
- Set `APP_DEBUG=true` temporarily
- Check logs in Railway dashboard
