# 🚀 AWS EC2 Deployment Guide - MyStore Application

Энэхүү заавар нь **AWS EC2** дээр MyStore апп-ийг эхнээс нь deploy хийх бүрэн процессыг алхам алхамаар тайлбарласан болно.

---

## 📋 Шаардлагатай зүйлс

- AWS аккаунт (бүртгэлгүй бол: https://aws.amazon.com/)
- SSH client (Windows: PuTTY эсвэл Git Bash, Mac/Linux: Terminal)
- Domain нэр (жишээ: yourstore.com) - Сонголт
- Credit/Debit карт (AWS-д)

---

## 📌 АЛХАМ 1: AWS EC2 Instance Үүсгэх

### 1.1 AWS Console руу нэвтрэх
1. https://console.aws.amazon.com/ руу орох
2. Нэвтрэх (Login)
3. **EC2** үйлчилгээг хайж олох

### 1.2 EC2 Instance эхлүүлэх
1. **"Launch Instance"** товч дарах
2. Дараах тохиргоог хийх:

#### Нэр өгөх:
```
Name: mystore-production
```

#### AMI (Operating System) сонгох:
```
✅ Ubuntu Server 22.04 LTS (Free tier eligible)
```

#### Instance Type сонгох:
```
Эхлээд: t2.micro (Free tier - 1GB RAM)
Санал: t2.small эсвэл t3.small (2GB RAM) - илүү сайн
```

#### Key Pair үүсгэх (Маш чухал!):
1. **"Create new key pair"** дарах
2. Нэр өгөх: `mystore-key`
3. Type: **RSA**
4. Format:
   - Windows (PuTTY): `.ppk`
   - Mac/Linux: `.pem`
5. **"Create key pair"** дарах
6. ⚠️ **Файлыг аюулгүй хадгалах** (энэ файл дахин татаж авах боломжгүй!)

#### Network Settings:
1. **"Create security group"** сонгох
2. Security group name: `mystore-sg`
3. Description: `Security group for MyStore application`
4. Дараах портуудыг нээх:

| Type | Protocol | Port | Source | Description |
|------|----------|------|--------|-------------|
| SSH | TCP | 22 | My IP | SSH нэвтрэх |
| HTTP | TCP | 80 | 0.0.0.0/0 | Website |
| HTTPS | TCP | 443 | 0.0.0.0/0 | Secure Website |

**Зөвлөмж**: SSH (22) портыг зөвхөн таны IP-ээс нээх (Security↑)

#### Storage:
```
Size: 20 GB (Free tier: 30GB хүртэл)
Volume Type: gp3 (SSD)
```

#### Advanced Details:
```
Энэ хэсгийг байнга үлдээж болно
```

3. **"Launch Instance"** дарах
4. 30-60 секунд хүлээх

---

## 📌 АЛХАМ 2: EC2 Instance Руу Холбогдох (SSH)

### 2.1 Instance IP хаяг авах
1. EC2 Dashboard → Instances
2. Таны instance-г сонгох
3. **Public IPv4 address** хуулах (жишээ: `54.123.45.67`)

### 2.2 SSH холболт

#### Windows (PuTTY):
1. PuTTY татаж суулгах: https://www.putty.org/
2. PuTTYgen ашиглан `.ppk` файлыг load хийх
3. PuTTY нээх:
   - Host Name: `ubuntu@54.123.45.67` (таны IP)
   - Port: `22`
   - Connection → SSH → Auth → Credentials: `.ppk` файл сонгох
   - Open дарах

#### Mac/Linux эсвэл Git Bash:
```bash
# Key файлын permission засах
chmod 400 ~/Downloads/mystore-key.pem

# SSH холбогдох
ssh -i ~/Downloads/mystore-key.pem ubuntu@54.123.45.67
```

**Амжилттай бол** terminal дээр энэ харагдана:
```
Welcome to Ubuntu 22.04 LTS
ubuntu@ip-172-31-12-34:~$
```

---

## 📌 АЛХАМ 3: Server Setup (Ubuntu)

Одоо server дээр байна. Дараах алхмуудыг дараалалтай ажиллуулна уу.

### 3.1 System Update
```bash
sudo apt update && sudo apt upgrade -y
```

### 3.2 Apache Web Server суулгах
```bash
sudo apt install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2
```

**Тест**: Browser дээр `http://YOUR_IP` руу орох. Apache default хуудас харагдана.

### 3.3 PHP 8.1 суулгах
```bash
sudo apt install php8.1 php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd -y

# Шалгах
php -v
```

### 3.4 MySQL Server суулгах
```bash
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo systemctl enable mysql
```

### 3.5 MySQL аюулгүй болгох
```bash
sudo mysql_secure_installation
```

**Асуултууд:**
- Set root password? **Y** → Хүчтэй нууц үг үүсгэх (жишээ: `MyStore@2024#Secure`)
- Remove anonymous users? **Y**
- Disallow root login remotely? **Y**
- Remove test database? **Y**
- Reload privilege tables? **Y**

### 3.6 Composer суулгах (PHP dependency manager)
```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Шалгах
composer --version
```

### 3.7 Git суулгах
```bash
sudo apt install git -y
git --version
```

---

## 📌 АЛХАМ 4: Database Үүсгэх

### 4.1 MySQL руу нэвтрэх
```bash
sudo mysql -u root -p
# Нууц үгээ оруулах
```

### 4.2 Database болон user үүсгэх
```sql
-- Database үүсгэх
CREATE DATABASE mystore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- User үүсгэх (хүчтэй нууц үг!)
CREATE USER 'mystore_user'@'localhost' IDENTIFIED BY 'SecurePass123!@#';

-- Эрх өгөх
GRANT SELECT, INSERT, UPDATE, DELETE ON mystore.* TO 'mystore_user'@'localhost';
FLUSH PRIVILEGES;

-- Гарах
EXIT;
```

⚠️ **Санах**: `SecurePass123!@#` - энэ нууц үгийг .env файлд ашиглана!

### 4.3 Database schema импорт хийх
```bash
# Таны database.sql файлыг байршуулсны дараа
sudo mysql -u root -p mystore < /tmp/database.sql
```

---

## 📌 АЛХАМ 5: Кодоо Upload Хийх

### 5.1 Option A: Git (Санал болгох)

GitHub репозитори үүсгэсэн бол:
```bash
# Web root руу очих
cd /var/www/html

# Репозиторио clone хийх
sudo git clone https://github.com/YOUR_USERNAME/mystore-v1.git mystore

# Эрх өгөх
sudo chown -R www-data:www-data /var/www/html/mystore
sudo chmod -R 755 /var/www/html/mystore
```

### 5.2 Option B: SCP/SFTP (Manual Upload)

**Таны local компьютер дээр:**
```bash
# Windows (WinSCP ашиглах эсвэл Git Bash)
scp -i mystore-key.pem -r C:/xampp/htdocs/mystore-v1 ubuntu@54.123.45.67:/tmp/

# Mac/Linux
scp -i ~/mystore-key.pem -r /path/to/mystore-v1 ubuntu@54.123.45.67:/tmp/
```

**Server дээр:**
```bash
# Файлуудыг зөв газарт зөөх
sudo mv /tmp/mystore-v1 /var/www/html/mystore
sudo chown -R www-data:www-data /var/www/html/mystore
sudo chmod -R 755 /var/www/html/mystore
```

### 5.3 Composer Dependencies суулгах
```bash
cd /var/www/html/mystore
composer install --no-dev --optimize-autoloader
```

---

## 📌 АЛХАМ 6: Production Тохиргоо

### 6.1 .env файл үүсгэх
```bash
cd /var/www/html/mystore
sudo nano .env
```

**Дараах агуулгыг оруулах:**
```env
DB_HOST=localhost
DB_USER=mystore_user
DB_PASS=SecurePass123!@#
DB_NAME=mystore

SITE_NAME="Your Store Name"
SITE_URL=http://YOUR_DOMAIN_OR_IP
ADMIN_EMAIL=your@email.com

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="Your Store Name"

SESSION_SECRET=YOUR_RANDOM_64_CHAR_STRING
ADMIN_DEFAULT_PASSWORD=ChangeThisSecurePassword123!
```

**Хадгалах:** `Ctrl + X` → `Y` → `Enter`

### 6.2 Random Session Secret үүсгэх
```bash
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```
Үр дүнг SESSION_SECRET-д хуулах

### 6.3 Upload хавтсууд үүсгэх
```bash
cd /var/www/html/mystore
sudo mkdir -p uploads/templates uploads/files logs
sudo chown -R www-data:www-data uploads logs
sudo chmod -R 775 uploads logs
```

### 6.4 Production тохиргоо (includes/config.php)
```bash
sudo nano includes/config.php
```

**Эдгээр мөрүүдийг засах:**
```php
// ЭНЭ ХЭСГИЙГ УСТГАХ ЭСВЭЛ ХААХ:
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

// PRODUCTION ДЭЭР:
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
```

### 6.5 .htaccess бүх sensitive хавтсанд
```bash
# Admin хавтас
sudo nano /var/www/html/mystore/includes/.htaccess
```
Агуулга:
```apache
Order Deny,Allow
Deny from all
```

Мөн vendor, logs хавтсанд давтах

---

## 📌 АЛХАМ 7: Apache Virtual Host Тохируулах

### 7.1 Virtual Host файл үүсгэх
```bash
sudo nano /etc/apache2/sites-available/mystore.conf
```

**Агуулга:**
```apache
<VirtualHost *:80>
    ServerAdmin admin@yourdomain.com
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/mystore

    <Directory /var/www/html/mystore>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/mystore-error.log
    CustomLog ${APACHE_LOG_DIR}/mystore-access.log combined

    # Security Headers
    Header always set X-Frame-Options "DENY"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</VirtualHost>
```

### 7.2 Site идэвхжүүлэх
```bash
# Default site идэвхгүй болгох
sudo a2dissite 000-default.conf

# MyStore site идэвхжүүлэх
sudo a2ensite mystore.conf

# Rewrite module идэвхжүүлэх
sudo a2enmod rewrite
sudo a2enmod headers

# Apache restart
sudo systemctl restart apache2
```

---

## 📌 АЛХАМ 8: SSL/HTTPS Тохируулах (Let's Encrypt)

### 8.1 Certbot суулгах
```bash
sudo apt install certbot python3-certbot-apache -y
```

### 8.2 SSL сертификат авах
```bash
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

**Асуултууд:**
- Email хаяг оруулах
- Terms of Service зөвшөөрөх (Y)
- Redirect to HTTPS? **2** (Redirect сонгох)

### 8.3 Auto-renewal тохируулах
```bash
# Шалгах (dry run)
sudo certbot renew --dry-run

# Cron job (автоматаар сэргээх)
sudo crontab -e
```

**Доор нь нэмэх:**
```
0 3 * * * certbot renew --quiet
```

### 8.4 .env файлд HTTPS засах
```bash
sudo nano /var/www/html/mystore/.env
```
```
SITE_URL=https://yourdomain.com
```

---

## 📌 АЛХАМ 9: Firewall Тохиргоо

```bash
# UFW (Uncomplicated Firewall) идэвхжүүлэх
sudo ufw allow OpenSSH
sudo ufw allow 'Apache Full'
sudo ufw enable

# Статус шалгах
sudo ufw status
```

---

## 📌 АЛХАМ 10: robots.txt засах

```bash
sudo nano /var/www/html/mystore/robots.txt
```

**28-р мөр:**
```
Sitemap: https://yourdomain.com/sitemap.xml
```

---

## 📌 АЛХАМ 11: Эцсийн Шалгалтууд

### 11.1 Permissions дахин шалгах
```bash
cd /var/www/html/mystore
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo chmod 600 .env
sudo chmod -R 775 uploads logs
```

### 11.2 Apache error log шалгах
```bash
sudo tail -f /var/log/apache2/mystore-error.log
```

### 11.3 Browser дээр тест хийх
1. `https://yourdomain.com` руу орох
2. Бүртгүүлэх тест
3. Login тест
4. Template авах тест
5. Email ирж байгаа эсэх

---

## 📌 АЛХАМ 12: Backup Strategy

### 12.1 Database backup script
```bash
sudo nano /home/ubuntu/backup-db.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/home/ubuntu/backups/db"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u mystore_user -p'SecurePass123!@#' mystore > $BACKUP_DIR/mystore_$DATE.sql

# Хуучин backup устгах (7 хоног өмнөхийг)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
```

```bash
chmod +x /home/ubuntu/backup-db.sh

# Crontab (өдөр бүр 2:00AM)
crontab -e
```
Нэмэх:
```
0 2 * * * /home/ubuntu/backup-db.sh
```

### 12.2 Files backup
```bash
sudo nano /home/ubuntu/backup-files.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/home/ubuntu/backups/files"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz /var/www/html/mystore/uploads

# 7 хоног хуучин backup устгах
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
chmod +x /home/ubuntu/backup-files.sh
```

---

## 📌 Monitoring & Maintenance

### Application logs шалгах
```bash
# PHP errors
sudo tail -f /var/www/html/mystore/logs/php_errors.log

# Application logs
sudo tail -f /var/www/html/mystore/logs/app.log

# Apache errors
sudo tail -f /var/log/apache2/mystore-error.log
```

### Server resources шалгах
```bash
# CPU & Memory
htop

# Disk usage
df -h

# MySQL status
sudo systemctl status mysql
```

---

## 🎯 Deployment Checklist

✅ **Before Deployment:**
- [ ] `.env` файл production тохиргоотой
- [ ] `display_errors = 0` config.php дээр
- [ ] Database backup хийсэн
- [ ] Security headers нэмсэн
- [ ] HTTPS идэвхжсэн
- [ ] Firewall тохируулсан
- [ ] Strong passwords бүх газар
- [ ] robots.txt domain засчихсан

✅ **After Deployment:**
- [ ] Website ажиллаж байгаа эсэх
- [ ] SSL сертификат valid эсэх
- [ ] Email илгээлт ажиллаж байгаа
- [ ] File upload ажиллаж байгаа
- [ ] Database холболт ажиллаж байгаа
- [ ] Backup script ажиллаж байгаа
- [ ] Admin хэсэг хандалттай эсэх
- [ ] Payment flow (хэрэв байвал)

---

## 🆘 Common Issues & Solutions

### Issue 1: 500 Internal Server Error
```bash
# Error log шалгах
sudo tail -50 /var/log/apache2/mystore-error.log

# Permissions
sudo chown -R www-data:www-data /var/www/html/mystore
```

### Issue 2: Database connection failed
```bash
# MySQL ажиллаж байгаа эсэх
sudo systemctl status mysql

# .env credentials шалгах
sudo nano /var/www/html/mystore/.env
```

### Issue 3: Composer dependencies error
```bash
cd /var/www/html/mystore
composer install --no-dev
sudo chown -R www-data:www-data vendor/
```

### Issue 4: Email илгээгдэхгүй байна
```bash
# SMTP тохиргоо шалгах
# Gmail: App Password үүсгэх шаардлагатай
# https://myaccount.google.com/apppasswords
```

---

## 📞 Support

- AWS Documentation: https://docs.aws.amazon.com/ec2/
- Ubuntu PHP: https://www.php.net/manual/en/install.unix.php
- Let's Encrypt: https://letsencrypt.org/docs/

---

## 🎉 Deployment Complete!

Таны MyStore апп одоо **production** дээр ажиллаж байна!

**Next Steps:**
1. Domain-ээ AWS Route 53 эсвэл Cloudflare дээр point хий
2. Google Analytics нэм
3. Email marketing setup хий
4. Regular backups хий
5. Monitor logs өдөр бүр

**Амжилт хүсье!** 🚀
