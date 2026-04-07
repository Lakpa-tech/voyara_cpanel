# VOYARA TRAVEL BOOKING SYSTEM
## Complete Deployment Guide — AWS Ubuntu + Apache

---

## FOLDER STRUCTURE

```
voyara/
├── .htaccess                  # Apache URL rewriting + security headers
├── database.sql               # Full MySQL schema + seed data
├── config/
│   ├── app.php                # App constants, paths, debug mode
│   ├── database.php           # DB credentials (use env vars in prod)
│   └── bootstrap.php          # Autoloader, session, helpers
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── PackageController.php
│   │   ├── BookingController.php
│   │   ├── ReviewController.php
│   │   ├── AdminController.php
│   │   └── AgentController.php
│   ├── models/
│   │   ├── DB.php             # PDO singleton
│   │   ├── Session.php        # Secure session management
│   │   ├── Auth.php           # Multi-role auth
│   │   ├── CSRF.php           # CSRF token generation/verification
│   │   ├── Validator.php      # Input validation
│   │   ├── FileUploader.php   # Secure file upload
│   │   ├── UserModel.php
│   │   ├── PackageModel.php
│   │   ├── BookingModel.php
│   │   ├── ReviewModel.php
│   │   ├── AdminModel.php
│   │   ├── AgentModel.php
│   │   └── SettingsModel.php
│   └── views/
│       ├── partials/          # header, footer, flash, 404, package_card
│       ├── user/              # home, packages, package_detail, dashboard, booking_detail, login, register, profile
│       ├── admin/             # login, dashboard, packages/, bookings/, users/, agents/, reviews/, settings
│       └── agent/             # login, dashboard, bookings, booking_detail
├── public/
│   └── index.php              # Front controller / router
├── assets/
│   ├── css/
│   │   ├── main.css           # Frontend styles (Bootstrap 5 + custom)
│   │   └── admin.css          # Admin panel styles
│   └── js/
│       ├── main.js            # Frontend JS (scroll, reveal, lazy-load)
│       └── admin.js           # Admin JS (sidebar, file preview)
└── uploads/
    ├── packages/              # Package cover + gallery images
    ├── receipts/              # Payment receipt uploads
    └── avatars/               # User avatar uploads
```

---

## STEP 1 — SERVER SETUP (Ubuntu 22.04 on AWS EC2)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache, PHP 8.2, MySQL client
sudo apt install -y apache2 php8.2 php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-fileinfo php8.2-gd libapache2-mod-php8.2

# Enable Apache modules
sudo a2enmod rewrite headers
sudo systemctl restart apache2
```

---

## STEP 2 — MySQL DATABASE (AWS RDS or local)

```sql
-- Create database and user
CREATE DATABASE voyara_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'voyara_user'@'%' IDENTIFIED BY 'YourStrongPassword123!';
GRANT ALL PRIVILEGES ON voyara_db.* TO 'voyara_user'@'%';
FLUSH PRIVILEGES;
```

```bash
# Import schema from local or EC2
mysql -h YOUR_RDS_ENDPOINT -u voyara_user -p voyara_db < database.sql
```

---

## STEP 3 — DEPLOY APPLICATION FILES

```bash
# Upload to EC2 (from local machine)
scp -r -i your-key.pem voyara/ ubuntu@YOUR_EC2_IP:/var/www/

# Set permissions
sudo chown -R www-data:www-data /var/www/voyara
sudo chmod -R 755 /var/www/voyara
sudo chmod -R 775 /var/www/voyara/uploads
sudo mkdir -p /var/www/voyara/logs
sudo chmod 775 /var/www/voyara/logs
```

---

## STEP 4 — APACHE VIRTUAL HOST

```bash
sudo nano /etc/apache2/sites-available/voyara.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/voyara

    <Directory /var/www/voyara>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Block sensitive directories
    <DirectoryMatch "^/var/www/voyara/(config|app|logs)">
        Require all denied
    </DirectoryMatch>

    ErrorLog  ${APACHE_LOG_DIR}/voyara_error.log
    CustomLog ${APACHE_LOG_DIR}/voyara_access.log combined
</VirtualHost>
```

```bash
sudo a2ensite voyara.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

---

## STEP 5 — ENVIRONMENT VARIABLES

Set these as Apache environment variables (never hardcode in files):

```bash
sudo nano /etc/apache2/sites-available/voyara.conf
# Add inside <VirtualHost>:
SetEnv DB_HOST     your-rds-endpoint.amazonaws.com
SetEnv DB_PORT     3306
SetEnv DB_NAME     voyara_db
SetEnv DB_USER     voyara_user
SetEnv DB_PASS     YourStrongPassword123!
SetEnv APP_URL     https://yourdomain.com
SetEnv APP_ENV     production
```

---

## STEP 6 — SSL WITH CERTBOT (HTTPS)

```bash
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

---

## STEP 7 — AWS SECURITY GROUP

Open these inbound ports in your EC2 Security Group:
- **22**  — SSH (your IP only)
- **80**  — HTTP (0.0.0.0/0)
- **443** — HTTPS (0.0.0.0/0)
- **3306** — MySQL (EC2 security group → RDS security group only)

---

## STEP 8 — UPLOADS SYMLINK (if DocumentRoot ≠ project root)

The router serves uploads via the `/uploads` URL path.
Ensure Apache can serve `/var/www/voyara/uploads/` directly.

---

## DEFAULT CREDENTIALS

| Role  | Email               | Password    |
|-------|---------------------|-------------|
| Admin | admin@voyara.com    | Admin@123   |

**⚠️ Change the admin password immediately after first login via the database:**
```sql
UPDATE admins SET password = '$2y$12$WvfBFLi3Zv6Bbhj87.VAj.N8I.4q.3YXH45hlFRW1vZhUsJ8pS04y%' WHERE email = 'admin@voyara.com';

-- Generate hash with: php -r "echo password_hash('YourNewPassword', PASSWORD_BCRYPT, ['cost'=>12]);"
```

---

## URL STRUCTURE

| URL                          | Description              |
|------------------------------|--------------------------|
| `/`                          | Homepage                 |
| `/packages`                  | Package listing + filters|
| `/packages/{slug}`           | Package detail + booking |
| `/login` `/register`         | User auth                |
| `/dashboard`                 | User dashboard           |
| `/bookings/{id}`             | Booking detail + payment |
| `/admin/login`               | Admin login              |
| `/admin/dashboard`           | Admin dashboard          |
| `/admin/packages`            | Package management       |
| `/admin/bookings`            | Booking management       |
| `/admin/users`               | User management          |
| `/admin/agents`              | Agent management         |
| `/admin/reviews`             | Review moderation        |
| `/admin/settings`            | Site settings            |
| `/agent/login`               | Agent login              |
| `/agent/dashboard`           | Agent dashboard          |
| `/agent/bookings`            | Agent bookings           |

---

## SECURITY CHECKLIST

- [x] PDO prepared statements on all queries
- [x] CSRF token on every POST form
- [x] bcrypt password hashing (cost=12)
- [x] Secure session configuration (httponly, samesite, strict_mode)
- [x] Session ID regeneration every 5 minutes
- [x] File upload MIME validation via finfo (not $_FILES['type'])
- [x] Input sanitization via Validator class
- [x] Output escaping via `e()` helper (htmlspecialchars)
- [x] .htaccess blocks direct access to /config, /app, /logs
- [x] Apache security headers (X-Frame-Options, X-Content-Type, etc.)
- [x] Role-based access control (user / admin / agent)
- [x] Credentials from environment variables (not hardcoded)

---

## CRON JOB (optional — session cleanup)

```bash
# Add to crontab: crontab -e
0 2 * * * php /var/www/voyara/public/index.php cli:cleanup >> /var/www/voyara/logs/cron.log 2>&1
```

---

## SUPPORT

Site Email: Configure at `/admin/settings`
