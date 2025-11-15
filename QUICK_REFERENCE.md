# 🚂 Railway Deployment - Quick Reference Card

## 📋 Deployment in 5 Steps

### 1️⃣ Push to GitHub (2 min)
```bash
git init
git add .
git commit -m "Railway deployment"
git remote add origin https://github.com/USERNAME/grow-crm.git
git push -u origin main
```

### 2️⃣ Setup Railway (3 min)
1. https://railway.app → Login with GitHub
2. New Project → Add MySQL → Add Redis
3. Add GitHub Repo → Select `grow-crm`

### 3️⃣ Environment Variables (10 min)
Copy from `.env.railway.example` to Railway Variables tab

**Critical ones:**
```env
APP_KEY=base64:xxx  # Generate with: php artisan key:generate --show
DB_HOST=${{MySQL.MYSQLHOST}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
REDIS_HOST=${{Redis.REDISHOST}}
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}
```

### 4️⃣ Import Database (10 min)
```bash
mysql -h HOST -P PORT -u USER -pPASS DATABASE < growcrm_landlord.sql
mysql -h HOST -P PORT -u USER -pPASS DATABASE < growsaas-tenant.sql
```

### 5️⃣ Deploy! (5 min)
1. Railway auto-builds
2. Settings → Networking → Generate Domain
3. Visit: https://yourapp.up.railway.app

---

## 🔑 Essential Railway Variables

```env
# App
APP_NAME=Grow CRM
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_KEY
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}

# Database (use Railway MySQL service)
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Redis (use Railway Redis service)
REDIS_HOST=${{Redis.REDISHOST}}
REDIS_PASSWORD=${{Redis.REDISPASSWORD}}
REDIS_PORT=${{Redis.REDISPORT}}

# Other
PORT=8080
LOG_LEVEL=error
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 🆘 Quick Troubleshooting

| Issue | Fix |
|-------|-----|
| Build fails | Check Dockerfile exists, review logs |
| Database error | Verify MySQL variables, check import |
| 500 error | Check APP_KEY set, view Railway logs |
| Won't start | Verify PORT=8080, check all env vars |
| Assets missing | Run in console: `php artisan storage:link` |

---

## 📞 Quick Links

- **Railway Dashboard:** https://railway.app/dashboard
- **Railway Docs:** https://docs.railway.app
- **Railway Discord:** https://discord.gg/railway
- **Generate APP_KEY:** `php artisan key:generate --show`

---

## 📚 Documentation Files

| File | Purpose | Time |
|------|---------|------|
| README_RAILWAY.md | Main overview | 5 min read |
| RAILWAY_QUICKSTART.md | Fast deployment | 30 min deploy |
| RAILWAY_DEPLOYMENT.md | Complete guide | 60 min deploy |
| RAILWAY_CHECKLIST.md | Step-by-step | 45 min deploy |

---

## 💰 Cost

- **Free Tier:** $5 credit/month (enough for small apps)
- **Typical Usage:** $0-10/month
- **Pro Tier:** $20/month (backups, more resources)

---

## ✅ Pre-Flight Checklist

- [ ] GitHub account created
- [ ] Railway account created
- [ ] Code committed to git
- [ ] All Railway files present
- [ ] Ready to deploy!

---

**Start:** [README_RAILWAY.md](README_RAILWAY.md)
**Quick:** [RAILWAY_QUICKSTART.md](RAILWAY_QUICKSTART.md)
**Complete:** [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md)
