# ✅ Railway Deployment Checklist

Use this checklist to ensure smooth deployment.

---

## 📋 Pre-Deployment

- [ ] GitHub account created
- [ ] Railway account created (linked with GitHub)
- [ ] Project files ready locally
- [ ] All Railway deployment files present (see below)

### Required Files Check

Run this to verify all files exist:
```bash
cd "/home/snake/my project/grow_sass"
ls -la Dockerfile railway.json nixpacks.toml .dockerignore docker/
```

You should have:
- ✓ `Dockerfile`
- ✓ `railway.json`
- ✓ `nixpacks.toml`
- ✓ `.dockerignore`
- ✓ `docker/nginx.conf`
- ✓ `docker/site.conf`
- ✓ `docker/supervisord.conf`
- ✓ `docker/start.sh`

---

## 🌐 GitHub Setup

- [ ] Git repository initialized
- [ ] All files committed
- [ ] GitHub repository created
- [ ] Code pushed to GitHub
  ```bash
  git remote add origin https://github.com/YOUR_USERNAME/grow-crm.git
  git push -u origin main
  ```

---

## 🚂 Railway Project Setup

- [ ] Railway project created
- [ ] MySQL database service added
- [ ] Redis service added (optional but recommended)
- [ ] GitHub repository connected

---

## 🔧 Environment Variables

- [ ] All environment variables added to Railway
- [ ] APP_KEY generated and added
  ```bash
  php artisan key:generate --show
  ```
- [ ] MySQL variables configured:
  - [ ] `DB_HOST=${{MySQL.MYSQLHOST}}`
  - [ ] `DB_PORT=${{MySQL.MYSQLPORT}}`
  - [ ] `DB_DATABASE=${{MySQL.MYSQLDATABASE}}`
  - [ ] `DB_USERNAME=${{MySQL.MYSQLUSER}}`
  - [ ] `DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}`
- [ ] Redis variables configured (if using Redis):
  - [ ] `REDIS_HOST=${{Redis.REDISHOST}}`
  - [ ] `REDIS_PASSWORD=${{Redis.REDISPASSWORD}}`
  - [ ] `REDIS_PORT=${{Redis.REDISPORT}}`
- [ ] `APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}`
- [ ] `PORT=8080`

---

## 💾 Database Setup

- [ ] Database connection details obtained from Railway
- [ ] MySQL client or TablePlus installed
- [ ] Connected to Railway MySQL successfully
- [ ] `growcrm_landlord.sql` imported
- [ ] `growsaas-tenant.sql` imported
- [ ] Database tables verified (run `SHOW TABLES;`)

---

## 🚀 Deployment

- [ ] Initial deployment triggered from GitHub
- [ ] Build logs checked (no errors)
- [ ] Application started successfully
- [ ] Railway domain generated
- [ ] Application accessible via Railway URL

---

## 🧪 Testing

- [ ] Application loads without errors
- [ ] Database connection working
- [ ] Can access login page
- [ ] Can create user account
- [ ] Can login successfully
- [ ] File uploads work
- [ ] Images display correctly
- [ ] CRM features functional (contacts, deals, etc.)

---

## 📧 Email Configuration (Optional)

- [ ] Email service chosen (Resend.com recommended)
- [ ] Email service account created
- [ ] API key obtained
- [ ] Email variables added to Railway:
  - [ ] `MAIL_MAILER=smtp`
  - [ ] `MAIL_HOST=smtp.resend.com`
  - [ ] `MAIL_PASSWORD=re_your_api_key`
- [ ] Test email sent successfully

---

## 💳 Payment Gateways (Optional)

- [ ] Stripe account created (if using Stripe)
- [ ] Stripe API keys added to Railway
- [ ] Razorpay account created (if using Razorpay)
- [ ] Razorpay API keys added to Railway
- [ ] Payment processing tested

---

## 🌍 Custom Domain (Optional)

- [ ] Custom domain purchased
- [ ] Domain added in Railway settings
- [ ] DNS records configured (CNAME to Railway)
- [ ] DNS propagation complete (check with `dig yourdomain.com`)
- [ ] HTTPS certificate issued by Railway
- [ ] Application accessible via custom domain
- [ ] `APP_URL` updated to custom domain

---

## 🔒 Security

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong APP_KEY generated
- [ ] Database passwords secured
- [ ] API keys stored in Railway variables (not in code)
- [ ] `.env` file not committed to GitHub
- [ ] Sensitive files in `.gitignore`

---

## 📊 Monitoring

- [ ] Railway logs accessible
- [ ] Application logging working
- [ ] Error tracking setup
- [ ] Resource usage monitored
- [ ] Deployment notifications configured

---

## 💾 Backups

- [ ] Database backup strategy planned
- [ ] Manual backup taken
- [ ] Backup schedule created (if on Railway Pro)
- [ ] File storage strategy decided (S3 for production)
- [ ] Backup restore tested

---

## 📚 Documentation

- [ ] Deployment documentation reviewed
- [ ] Admin credentials documented (securely)
- [ ] API keys documented (securely)
- [ ] Team members have access
- [ ] Support contacts noted

---

## 🎯 Post-Deployment

- [ ] Application URL shared with client
- [ ] Admin account created
- [ ] Initial data setup complete
- [ ] User training scheduled/completed
- [ ] Support plan established
- [ ] Monitoring alerts configured

---

## ✅ Final Verification

Run through this final checklist:

1. **Visit your application:**
   - [ ] `https://yourapp.up.railway.app` loads
   - [ ] No 500 errors
   - [ ] No console errors (F12 → Console)

2. **Test core features:**
   - [ ] Login works
   - [ ] Dashboard displays
   - [ ] Create a contact
   - [ ] Create a deal
   - [ ] Upload a file
   - [ ] Send an email (if configured)

3. **Check logs:**
   - [ ] No errors in Railway logs
   - [ ] Application logging correctly
   - [ ] Queue workers running

4. **Performance:**
   - [ ] Page loads quickly (<3 seconds)
   - [ ] Images load properly
   - [ ] No timeout errors

---

## 🆘 Troubleshooting Reference

### Build Failed
```
✓ Check Dockerfile exists
✓ Check all files in docker/ folder exist
✓ Review build logs for specific error
✓ Verify Node.js and Composer can install dependencies
```

### Database Connection Error
```
✓ Verify MySQL service is running in Railway
✓ Check environment variables syntax
✓ Ensure database is imported
✓ Test connection from Railway console
```

### 500 Internal Server Error
```
✓ Check Railway application logs
✓ Verify APP_KEY is set
✓ Check database connection
✓ Verify storage permissions
```

### Application Won't Start
```
✓ Check PORT=8080 is set
✓ Verify all required environment variables
✓ Check Docker container logs
✓ Verify supervisor is running services
```

---

## 📞 Support Resources

- **Railway Discord:** https://discord.gg/railway
- **Railway Docs:** https://docs.railway.app
- **Laravel Docs:** https://laravel.com/docs
- **Your Documentation:** Documentation.pdf

---

## 🎉 Deployment Complete!

Once all items are checked:

✅ **Your application is successfully deployed!**

**Application URL:** `https://yourapp.up.railway.app`

**Next Steps:**
- Monitor application performance
- Setup regular backups
- Plan for scaling if needed
- Collect user feedback
- Iterate and improve

---

**Estimated Total Time:** 30-60 minutes (first time)
**Ongoing Cost:** FREE with Railway's $5/month credit
**Difficulty:** ⭐⭐⭐ (Medium)

Good luck with your deployment! 🚀
