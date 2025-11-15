# 🚂 Deploy Grow CRM to Railway.app - Complete Guide

## 📋 What You'll Get

- **FREE Hosting** on Railway.app (with $5 free credit monthly)
- **FREE Domain** (Railway provides: `yourapp.up.railway.app`)
- **MySQL Database** (included)
- **Redis** for caching and queues (included)
- **Auto-deployments** from GitHub
- **HTTPS** automatically configured

## 💰 Cost Breakdown (Railway Free Tier)

Railway gives you **$5 FREE credit every month**, which is enough for:
- Small to medium traffic applications
- Development and testing
- Portfolio projects

**What's Included:**
- Web application hosting
- MySQL database
- Redis cache
- Automatic HTTPS/SSL
- Custom domain support

## 🎯 Prerequisites

Before starting, you need:

1. **GitHub Account** (free) - https://github.com
2. **Railway Account** (free) - https://railway.app
3. **This project code** (you already have it!)

Optional (for custom domain):
- Custom domain name (can use Railway's free subdomain instead)

## 📝 Step-by-Step Deployment Guide

### **STEP 1: Prepare Your Project for GitHub**

#### 1.1 Initialize Git Repository (if not already done)

```bash
cd "/home/snake/my project/grow_sass"
git init
git add .
git commit -m "Initial commit - Grow CRM for Railway deployment"
```

#### 1.2 Create GitHub Repository

1. Go to https://github.com
2. Click the **"+"** button (top right) → **"New repository"**
3. Fill in:
   - **Repository name:** `grow-crm`
   - **Description:** `Multi-tenant CRM application`
   - **Visibility:** Private (recommended) or Public
4. Click **"Create repository"**

#### 1.3 Push Your Code to GitHub

```bash
# Add your GitHub repository as remote
git remote add origin https://github.com/YOUR_USERNAME/grow-crm.git

# Push your code
git branch -M main
git push -u origin main
```

**Replace** `YOUR_USERNAME` with your actual GitHub username!

---

### **STEP 2: Create Railway Account and Project**

#### 2.1 Sign Up for Railway

1. Go to https://railway.app
2. Click **"Login"** or **"Start a New Project"**
3. Sign up with **GitHub account** (recommended for easy integration)
4. Verify your email

#### 2.2 Create New Project

1. Click **"New Project"**
2. You'll see options for deployment

---

### **STEP 3: Add MySQL Database**

#### 3.1 Add MySQL Service

1. In your Railway project, click **"+ New"**
2. Select **"Database"**
3. Choose **"Add MySQL"**
4. Railway will automatically:
   - Create a MySQL instance
   - Generate connection credentials
   - Set environment variables

#### 3.2 Note Your Database Variables

Railway automatically creates these variables:
- `MYSQLHOST`
- `MYSQLPORT`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`
- `MYSQL_URL`

---

### **STEP 4: Add Redis (Optional but Recommended)**

#### 4.1 Add Redis Service

1. Click **"+ New"** again
2. Select **"Database"**
3. Choose **"Add Redis"**
4. Railway creates:
   - `REDISHOST`
   - `REDISPORT`
   - `REDISPASSWORD`
   - `REDIS_URL`

---

### **STEP 5: Deploy Your Application**

#### 5.1 Deploy from GitHub

1. Click **"+ New"** in your project
2. Select **"GitHub Repo"**
3. If first time:
   - Click **"Configure GitHub App"**
   - Grant Railway access to your repositories
4. Select your `grow-crm` repository
5. Click **"Add variables"** or **"Deploy"**

#### 5.2 Configure Build Settings

1. Go to your service **Settings**
2. Under **"Build"**, Railway should auto-detect the Dockerfile
3. If not, set:
   - **Builder:** Dockerfile
   - **Dockerfile Path:** Dockerfile

---

### **STEP 6: Configure Environment Variables**

This is the **MOST IMPORTANT** step!

#### 6.1 Add Environment Variables

1. Click on your **web service** (the one from GitHub)
2. Go to **"Variables"** tab
3. Click **"RAW Editor"**
4. Copy and paste these variables:

```env
# Application
APP_NAME=Grow CRM
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}

# Generate APP_KEY - see step 6.2 below
APP_KEY=

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Database - These use Railway's MySQL variables
DB_CONNECTION=tenant
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Landlord Database (same MySQL instance)
LANDLORD_DB_DATABASE=${{MySQL.MYSQLDATABASE}}

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Redis - These use Railway's Redis variables
REDIS_HOST=${{Redis.REDISHOST}}
REDIS_PASSWORD=${{Redis.REDISPASSWORD}}
REDIS_PORT=${{Redis.REDISPORT}}

# Mail (Free option: Resend.com - see step 8)
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME=Grow CRM

# Multi-tenancy
TENANCY_DETECTION=domain

# Railway
PORT=8080
```

**Important Notes:**
- Replace `MySQL` with your actual MySQL service name in Railway
- Replace `Redis` with your actual Redis service name in Railway
- Railway uses `${{ServiceName.VARIABLE}}` syntax for cross-service variables

#### 6.2 Generate APP_KEY

You need to generate a unique application key. Two options:

**Option A: Locally (Recommended)**
```bash
cd "/home/snake/my project/grow_sass/application"
php artisan key:generate --show
```

Copy the output (looks like: `base64:xxxxxxxxxxxxxxxxxxxxx`) and add to Railway variables:
```
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
```

**Option B: In Railway Console (after first deployment)**
1. Go to your service → **Deployments** tab
2. Click latest deployment → **View Logs**
3. Once running, click **"..."** → **"Service Shell"**
4. Run: `php artisan key:generate --show`
5. Copy output and add to Variables

---

### **STEP 7: Initialize Database**

#### 7.1 Import Database Schema

You have two options:

**Option A: Using Railway MySQL Console (Recommended)**

1. Go to your **MySQL service** in Railway
2. Click **"Data"** tab
3. Click **"Query"** or connect via MySQL client
4. Use the connection details shown

Then on your local machine:

```bash
cd "/home/snake/my project/grow_sass"

# Connect to Railway MySQL and import
mysql -h <MYSQLHOST> -P <MYSQLPORT> -u <MYSQLUSER> -p<MYSQLPASSWORD> <MYSQLDATABASE> < growcrm_landlord.sql
mysql -h <MYSQLHOST> -P <MYSQLPORT> -u <MYSQLUSER> -p<MYSQLPASSWORD> <MYSQLDATABASE> < growsaas-tenant.sql
```

Replace the `<VARIABLES>` with actual values from Railway!

**Option B: Using TablePlus / MySQL Workbench (Easier)**

1. Download TablePlus (free) or MySQL Workbench
2. Get connection details from Railway MySQL service
3. Connect to the database
4. Import `growcrm_landlord.sql`
5. Import `growsaas-tenant.sql`

#### 7.2 Verify Database Import

In Railway MySQL console or TablePlus:
```sql
SHOW TABLES;
```

You should see many tables from the CRM system.

---

### **STEP 8: Configure Free Email Service (Resend.com)**

For sending emails, use Resend's free tier:

#### 8.1 Create Resend Account

1. Go to https://resend.com
2. Sign up (free)
3. Verify your email
4. Go to **API Keys**
5. Create new API key
6. Copy the key (starts with `re_`)

#### 8.2 Add to Railway

In Railway environment variables, update:
```env
MAIL_PASSWORD=re_your_resend_api_key_here
```

**Free Tier Limits:**
- 100 emails/day
- 3,000 emails/month
- Perfect for testing and small projects

**Alternative Free Email Services:**
- SendGrid (100 emails/day free)
- Mailgun (5,000 emails/month for 3 months)
- Mailtrap (for testing only)

---

### **STEP 9: Deploy and Access Your Application**

#### 9.1 Trigger Deployment

1. Railway should auto-deploy when you push to GitHub
2. Or click **"Deploy"** button in Railway
3. Watch the build logs

#### 9.2 Monitor Deployment

1. Go to **Deployments** tab
2. Click on the latest deployment
3. Watch the logs - you should see:
   - Building Docker image
   - Installing dependencies
   - Starting services
   - "Deployment successful"

#### 9.3 Access Your Application

1. Go to your service **Settings**
2. Under **"Networking"**
3. Click **"Generate Domain"**
4. Railway gives you a URL like: `yourapp.up.railway.app`
5. Click the URL to access your CRM!

---

### **STEP 10: Setup Custom Domain (Optional)**

If you have a custom domain:

#### 10.1 Add Custom Domain

1. In your service **Settings**
2. Go to **"Networking"**
3. Click **"Custom Domain"**
4. Enter your domain: `yourdomain.com`

#### 10.2 Configure DNS

Railway shows you DNS records to add:

**For main domain:**
```
Type: CNAME
Name: @
Value: provided-by-railway.railway.app
```

**For subdomain:**
```
Type: CNAME
Name: www
Value: provided-by-railway.railway.app
```

**For multi-tenancy (wildcard):**
```
Type: CNAME
Name: *
Value: provided-by-railway.railway.app
```

#### 10.3 Update Environment Variables

```env
APP_URL=https://yourdomain.com
```

Redeploy the service.

---

### **STEP 11: Configure Multi-Tenancy**

For multi-tenant functionality:

#### 11.1 Database Setup

Each tenant needs a separate database. In Railway MySQL:

```sql
CREATE DATABASE growcrm_tenant2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE growcrm_tenant3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 11.2 Import Tenant Schema

```bash
mysql -h <HOST> -P <PORT> -u <USER> -p<PASS> growcrm_tenant2 < growsaas-tenant.sql
```

#### 11.3 Register Tenants

Connect to your landlord database and register tenants (check your table structure first).

---

## 🎉 Your Application is Now Live!

Access at: `https://yourapp.up.railway.app`

---

## 🔧 Post-Deployment Tasks

### 1. Test Core Functionality

- [ ] Application loads
- [ ] Can create user account
- [ ] Can login
- [ ] Database connections working
- [ ] Can upload files
- [ ] Can create CRM records (contacts, deals, etc.)

### 2. Configure Payment Gateways

Add to Railway environment variables:

```env
STRIPE_KEY=pk_live_xxxxx
STRIPE_SECRET=sk_live_xxxxx

RAZORPAY_KEY=rzp_live_xxxxx
RAZORPAY_SECRET=xxxxx

MOLLIE_KEY=live_xxxxx
```

### 3. Setup OpenAI (Optional)

```env
OPENAI_API_KEY=sk-xxxxx
```

### 4. Configure reCAPTCHA (Optional)

```env
RECAPTCHA_SITE_KEY=xxxxx
RECAPTCHA_SECRET_KEY=xxxxx
```

---

## 📊 Monitoring & Logs

### View Application Logs

1. Go to your service in Railway
2. Click **"Deployments"**
3. Click on active deployment
4. View real-time logs

### Common Log Locations

- **Application:** Check Railway logs
- **Nginx:** Included in Railway logs
- **Queue Workers:** Check Railway logs
- **Laravel:** All logs appear in Railway console

### Check Service Health

Railway provides:
- CPU usage
- Memory usage
- Network traffic
- Deployment history

---

## 🐛 Troubleshooting

### Application Won't Start

**Check:**
1. Build logs for errors
2. Environment variables are set correctly
3. APP_KEY is generated
4. Database connection variables are correct

**Fix:**
```bash
# In Railway console
php artisan config:clear
php artisan cache:clear
```

### Database Connection Error

**Check:**
1. MySQL service is running
2. Database variables are correct:
   - `${{MySQL.MYSQLHOST}}`
   - `${{MySQL.MYSQLPORT}}`
   - etc.
3. Database is imported

**Fix:**
In Railway console:
```bash
php artisan db:show
```

### 500 Internal Server Error

**Check application logs in Railway**

Common causes:
- Missing APP_KEY
- Wrong database credentials
- Missing environment variables

**Fix:**
1. Check Railway logs for specific error
2. Verify all environment variables
3. Redeploy service

### Assets Not Loading (CSS/JS)

**Fix:**
```bash
# In Railway console
php artisan storage:link
```

Or add to your Dockerfile startup script.

### Queue Jobs Not Processing

**Check Supervisor logs in Railway**

Queues are configured in the Dockerfile to run automatically via Supervisor.

**Verify:**
```bash
# In Railway console
supervisorctl status
```

---

## 💾 Backup Strategy

### Database Backups

Railway MySQL doesn't have automatic backups on free tier.

**Manual Backup:**
```bash
# Run this locally periodically
mysqldump -h <MYSQLHOST> -P <MYSQLPORT> -u <MYSQLUSER> -p<MYSQLPASSWORD> <MYSQLDATABASE> > backup_$(date +%Y%m%d).sql
```

**Automated Backups:**
- Upgrade to Railway Pro ($20/month) for automated backups
- Use external backup service
- Setup cron job to backup to AWS S3 or similar

### File Backups

If using local storage:
- Files are stored in the container (ephemeral)
- **Important:** Configure AWS S3 or similar for persistent file storage

Add to environment variables:
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=xxxxx
AWS_SECRET_ACCESS_KEY=xxxxx
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

---

## 🔄 Updating Your Application

### Push Updates

```bash
cd "/home/snake/my project/grow_sass"

# Make your changes
git add .
git commit -m "Update: description of changes"
git push origin main
```

Railway automatically:
1. Detects the push
2. Rebuilds the Docker image
3. Runs migrations
4. Deploys new version
5. Zero-downtime deployment

### Manual Redeploy

In Railway:
1. Go to **Deployments**
2. Click **"..."** on any deployment
3. Click **"Redeploy"**

---

## 💡 Tips & Best Practices

### 1. Environment Management

- Keep environment variables organized
- Never commit `.env` to GitHub
- Use Railway's variable groups for organization

### 2. Cost Management

- Monitor your Railway usage dashboard
- Free tier gives $5/month credit
- Optimize Docker image size
- Use Redis for caching to reduce database queries

### 3. Performance

- Enable Laravel caching:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- Use Redis for sessions and cache
- Optimize database queries
- Use CDN for static assets

### 4. Security

- Keep `APP_DEBUG=false` in production
- Use strong `APP_KEY`
- Keep dependencies updated
- Use HTTPS (automatic on Railway)
- Enable rate limiting

### 5. Scaling

When you outgrow free tier:
- Upgrade to Railway Pro ($20/month)
- Get more resources
- Vertical scaling (more RAM/CPU)
- Automated backups
- Better support

---

## 📚 Useful Railway Commands

### Railway CLI (Optional)

Install Railway CLI:
```bash
npm i -g @railway/cli
```

Useful commands:
```bash
# Login
railway login

# Link to project
railway link

# View logs
railway logs

# Open console
railway shell

# Add variables
railway variables

# Deploy
railway up
```

---

## 🆘 Getting Help

### Railway Resources

- **Docs:** https://docs.railway.app
- **Discord:** https://discord.gg/railway
- **Status:** https://status.railway.app

### Project Resources

- **Laravel Docs:** https://laravel.com/docs/11.x
- **Your Documentation:** Documentation.pdf

---

## ✅ Deployment Checklist

Before going live:

- [ ] Code pushed to GitHub
- [ ] Railway project created
- [ ] MySQL database added and imported
- [ ] Redis added (optional)
- [ ] All environment variables configured
- [ ] APP_KEY generated
- [ ] Domain configured (custom or Railway subdomain)
- [ ] HTTPS enabled (automatic)
- [ ] Email service configured and tested
- [ ] Payment gateways configured (if needed)
- [ ] Database backups scheduled
- [ ] Application tested thoroughly
- [ ] Monitoring setup
- [ ] Error logging working

---

## 🎊 Congratulations!

Your Grow CRM is now live on Railway with:
- ✅ Free hosting
- ✅ Free domain (Railway subdomain)
- ✅ Free MySQL database
- ✅ Free Redis cache
- ✅ Free HTTPS/SSL
- ✅ Auto-deployments from GitHub
- ✅ Professional multi-tenant CRM

**Your app is accessible at:**
`https://yourapp.up.railway.app`

---

## 📝 Quick Reference

### Important Files Created

| File | Purpose |
|------|---------|
| `Dockerfile` | Docker container configuration |
| `railway.json` | Railway deployment config |
| `nixpacks.toml` | Alternative builder config |
| `.dockerignore` | Files to exclude from Docker |
| `.env.railway.example` | Environment template |
| `docker/nginx.conf` | Nginx web server config |
| `docker/site.conf` | Site configuration |
| `docker/supervisord.conf` | Process manager config |
| `docker/start.sh` | Container startup script |

### Key Railway URLs

- **Dashboard:** https://railway.app/dashboard
- **Your Project:** https://railway.app/project/[your-project-id]
- **Your App:** https://yourapp.up.railway.app

---

**Last Updated:** $(date)
**Deployment Platform:** Railway.app
**Estimated Deployment Time:** 30-45 minutes
