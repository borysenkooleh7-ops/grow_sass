# 🚀 Railway.app - Quick Start Guide (5 Minutes)

## What You Need

1. GitHub account (free)
2. Railway account (free)
3. 30 minutes of your time

---

## 🎯 Quick Deployment Steps

### **STEP 1: Push to GitHub** (2 minutes)

```bash
cd "/home/snake/my project/grow_sass"

# Initialize git if not done
git init
git add .
git commit -m "Initial commit for Railway deployment"

# Create repo on GitHub.com, then:
git remote add origin https://github.com/YOUR_USERNAME/grow-crm.git
git branch -M main
git push -u origin main
```

---

### **STEP 2: Setup Railway** (3 minutes)

1. Go to https://railway.app
2. Click **"Login"** → Sign in with GitHub
3. Click **"New Project"**
4. Click **"+ New"** → **"Database"** → **"Add MySQL"**
5. Click **"+ New"** → **"Database"** → **"Add Redis"** (optional)
6. Click **"+ New"** → **"GitHub Repo"** → Select `grow-crm`

---

### **STEP 3: Configure Environment Variables** (10 minutes)

1. Click on your **web service**
2. Go to **"Variables"** tab
3. Click **"RAW Editor"**
4. Paste this (replace MySQL and Redis with your service names):

```env
APP_NAME=Grow CRM
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}
APP_KEY=

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=tenant
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

LANDLORD_DB_DATABASE=${{MySQL.MYSQLDATABASE}}

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=${{Redis.REDISHOST}}
REDIS_PASSWORD=${{Redis.REDISPASSWORD}}
REDIS_PORT=${{Redis.REDISPORT}}

MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME=Grow CRM

TENANCY_DETECTION=domain
PORT=8080
```

5. **Generate APP_KEY:**

Locally run:
```bash
cd application
php artisan key:generate --show
```

Copy the output and add to Railway variables:
```
APP_KEY=base64:xxxxxxxxxxxxx
```

---

### **STEP 4: Import Database** (10 minutes)

**Option A: Using MySQL Client**

Get connection details from Railway MySQL service, then:

```bash
cd "/home/snake/my project/grow_sass"

# Replace with your actual Railway MySQL credentials
mysql -h containers-us-west-xxx.railway.app -P 6379 -u root -p railway < growcrm_landlord.sql
mysql -h containers-us-west-xxx.railway.app -P 6379 -u root -p railway < growsaas-tenant.sql
```

**Option B: Using TablePlus (Easier)**

1. Download TablePlus (free): https://tableplus.com
2. Get connection details from Railway MySQL service
3. Create new connection with Railway credentials
4. Right-click → **"Import"** → Select `growcrm_landlord.sql`
5. Right-click → **"Import"** → Select `growsaas-tenant.sql`

---

### **STEP 5: Deploy!** (5 minutes)

1. Railway auto-deploys from GitHub
2. Watch the logs in **"Deployments"** tab
3. Once deployed, go to **"Settings"** → **"Networking"**
4. Click **"Generate Domain"**
5. Visit your app at: `https://yourapp.up.railway.app`

---

## 🎉 Done!

Your CRM is now live!

**Next Steps:**
- Configure email (Resend.com for free)
- Add payment gateways (Stripe, etc.)
- Setup custom domain (optional)

📖 **Full Guide:** See [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md) for detailed instructions.

---

## ⚠️ Common Issues

### Build Fails

**Check:**
- All environment variables are set
- APP_KEY is generated
- Dockerfile exists

### Database Connection Error

**Fix:**
- Verify MySQL service name in variables
- Check database is imported
- Verify `${{MySQL.VARIABLE}}` syntax

### 500 Error

**Fix:**
- Check Railway logs for specific error
- Ensure APP_KEY is set
- Verify database connection

---

## 📞 Need Help?

- **Full Guide:** [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md)
- **Railway Discord:** https://discord.gg/railway
- **Railway Docs:** https://docs.railway.app

---

**Total Time:** ~30 minutes
**Cost:** FREE (with $5 monthly credit)
