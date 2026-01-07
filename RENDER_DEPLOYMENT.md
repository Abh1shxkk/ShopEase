# ShopEase - Render Deployment Guide

## Prerequisites
- GitHub account with this repository pushed
- Render account (https://render.com)

## Deployment Steps

### Option 1: Blueprint Deployment (Recommended)

1. Push your code to GitHub
2. Go to Render Dashboard → **New** → **Blueprint**
3. Connect your GitHub repository
4. Render will detect `render.yaml` and create:
   - Web Service (Docker)
   - PostgreSQL Database
5. Set `APP_URL` to your Render URL (e.g., `https://shopease.onrender.com`)
6. Deploy!

### Option 2: Manual Deployment

#### Step 1: Create PostgreSQL Database

1. Go to Render Dashboard → **New** → **PostgreSQL**
2. Configure:
   - Name: `shopease-db`
   - Database: `shopease`
   - User: `shopease_user`
   - Region: Choose closest to your users
   - Plan: Free (or paid for production)
3. Click **Create Database**
4. Copy the **Internal Database URL** (starts with `postgres://`)

#### Step 2: Create Web Service

1. Go to Render Dashboard → **New** → **Web Service**
2. Connect your GitHub repository
3. Configure:
   - Name: `shopease`
   - Region: Same as database
   - Branch: `main`
   - Runtime: **Docker**
   - Dockerfile Path: `./Dockerfile`

#### Step 3: Set Environment Variables

In your Web Service settings, add these environment variables:

| Key | Value |
|-----|-------|
| `APP_NAME` | ShopEase |
| `APP_ENV` | production |
| `APP_DEBUG` | false |
| `APP_KEY` | (generate with `php artisan key:generate --show`) |
| `APP_URL` | https://your-app.onrender.com |
| `LOG_CHANNEL` | stderr |
| `DB_CONNECTION` | pgsql |
| `DATABASE_URL` | (paste Internal Database URL from Step 1) |
| `DB_SSLMODE` | require |
| `SESSION_DRIVER` | cookie |
| `CACHE_STORE` | file |
| `QUEUE_CONNECTION` | sync |
| `FILESYSTEM_DISK` | local |

**Optional (for full functionality):**

| Key | Value |
|-----|-------|
| `MAIL_MAILER` | smtp |
| `MAIL_HOST` | (your SMTP host) |
| `MAIL_PORT` | 587 |
| `MAIL_USERNAME` | (your SMTP username) |
| `MAIL_PASSWORD` | (your SMTP password) |
| `MAIL_FROM_ADDRESS` | noreply@yourdomain.com |
| `GOOGLE_CLIENT_ID` | (for Google OAuth) |
| `GOOGLE_CLIENT_SECRET` | (for Google OAuth) |
| `RAZORPAY_KEY_ID` | (for payments) |
| `RAZORPAY_KEY_SECRET` | (for payments) |

### Step 4: Deploy

1. Click **Create Web Service**
2. Wait for the build to complete (5-10 minutes first time)
3. Your app will be live at `https://your-app.onrender.com`

## Post-Deployment

### Run Database Seeders (Optional)

Connect to your service shell via Render dashboard:

```bash
php artisan db:seed
```

### Create Admin User

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('your-secure-password'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

## Troubleshooting

### Build Fails
- Check build logs in Render dashboard
- Ensure `composer.json` and `package.json` are valid
- Verify PHP version compatibility (8.2+)

### Database Connection Issues
- Verify `DATABASE_URL` is the **Internal** URL (not External)
- Ensure `DB_SSLMODE=require` is set
- Check database is in same region as web service

### Assets Not Loading
- Verify `npm run build` completed successfully
- Check `APP_URL` matches your Render URL
- Clear caches: `php artisan cache:clear`

### 500 Errors
- Set `APP_DEBUG=true` temporarily to see errors
- Check `LOG_CHANNEL=stderr` and view logs in Render dashboard
- Verify all required environment variables are set

## File Storage Note

Render's filesystem is ephemeral. For persistent file uploads:
1. Use AWS S3 or similar cloud storage
2. Configure `FILESYSTEM_DISK=s3` with appropriate credentials

## Free Tier Limitations

- Web service spins down after 15 minutes of inactivity
- First request after spin-down takes ~30 seconds
- PostgreSQL free tier: 1GB storage, expires after 90 days
