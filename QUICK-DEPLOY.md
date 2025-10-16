# ⚡ Quick Deploy Guide - Хурдан Заавар

Хэрэв та deployment-н туршлагатай бол энэ богино заавар ашигла.

---

## 🚀 30 Минутын Deployment

### 1️⃣ EC2 Setup (5 мин)
```bash
# Instance: Ubuntu 22.04 LTS, t2.small
# Security Group: 22, 80, 443
# Key: mystore-key.pem
```

### 2️⃣ Server Install (10 мин)
```bash
# Update
sudo apt update && sudo apt upgrade -y

# LAMP Stack
sudo apt install -y apache2 mysql-server php8.1 php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd git

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# MySQL Secure
sudo mysql_secure_installation
```

### 3️⃣ Database Setup (3 мин)
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE mystore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mystore_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT SELECT, INSERT, UPDATE, DELETE ON mystore.* TO 'mystore_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4️⃣ Upload Code (5 мин)
```bash
# Local machine:
scp -i mystore-key.pem -r mystore-v1 ubuntu@YOUR_IP:/tmp/

# Server:
sudo mv /tmp/mystore-v1 /var/www/html/mystore
cd /var/www/html/mystore
composer install --no-dev --optimize-autoloader
```

### 5️⃣ Configure (5 мин)
```bash
# .env файл
sudo nano .env
# DB credentials, SITE_URL, MAIL settings оруул

# Permissions
sudo chown -R www-data:www-data /var/www/html/mystore
sudo chmod -R 755 /var/www/html/mystore
sudo chmod 600 .env
sudo mkdir -p uploads/templates uploads/files logs
sudo chmod -R 775 uploads logs

# Production config
sudo nano includes/config.php
# display_errors = 0 болго
```

### 6️⃣ Apache (2 мин)
```bash
sudo nano /etc/apache2/sites-available/mystore.conf
```
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/mystore
    <Directory /var/www/html/mystore>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
```bash
sudo a2dissite 000-default.conf
sudo a2ensite mystore.conf
sudo a2enmod rewrite headers
sudo systemctl restart apache2
```

### 7️⃣ SSL (2 мин)
```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### 8️⃣ Test ✅
- Browser: https://yourdomain.com
- Бүртгүүлэх
- Login
- Template авах

---

## 🔧 One-Liner Commands

### Бүх dependency нэг дор суулгах:
```bash
sudo apt update && sudo apt install -y apache2 mysql-server php8.1 php8.1-{mysql,mbstring,xml,curl,zip,gd} git && curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### Permissions бүгдийг засах:
```bash
cd /var/www/html/mystore && sudo find . -type f -exec chmod 644 {} \; && sudo find . -type d -exec chmod 755 {} \; && sudo chmod 600 .env && sudo chmod -R 775 uploads logs && sudo chown -R www-data:www-data .
```

### Database import:
```bash
sudo mysql -u root -p mystore < database.sql
```

---

## 📝 Checklist

- [ ] EC2 instance running
- [ ] SSH холбогдож байгаа
- [ ] Apache ажиллаж байгаа
- [ ] MySQL ажиллаж байгаа
- [ ] Code upload хийсэн
- [ ] .env файл тохируулсан
- [ ] Database үүсгэсэн
- [ ] Composer install хийсэн
- [ ] Permissions зөв
- [ ] Virtual Host тохируулсан
- [ ] SSL суурилуулсан
- [ ] Website ажиллаж байгаа

---

## 🆘 Quick Fixes

**500 Error:**
```bash
sudo tail -50 /var/log/apache2/error.log
sudo chown -R www-data:www-data /var/www/html/mystore
```

**Database Error:**
```bash
sudo systemctl status mysql
sudo nano /var/www/html/mystore/.env
```

**Permission Denied:**
```bash
sudo chmod -R 755 /var/www/html/mystore
sudo chown -R www-data:www-data /var/www/html/mystore
```

---

Дэлгэрэнгүй: `DEPLOYMENT.md` файл үз
