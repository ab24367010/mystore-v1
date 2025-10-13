# MYSTORE

## COMPOSER
```bash
composer -V
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
```

## GITHUB
``` bash
.gitignore

# Нууц мэдээлэл
.env

# Vendor
/vendor/

# Composer
composer.lock

# Uploads
/uploads/templates/*
/uploads/files/*
!.gitkeep

# Logs
*.log

# OS файлууд
.DS_Store
Thumbs.db
```

## .env
```bash
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password
DB_NAME=mystore

SITE_NAME="Template Store"
SITE_URL=http://localhost/mystore-v1
ADMIN_EMAIL=mnangl74@gmail.com

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mnangl74@gmail.com
MAIL_PASSWORD=mailpassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mnangl74@gmail.com
MAIL_FROM_NAME="Template Store"

SESSION_SECRET=your-random-secret-key-here-change-this
ADMIN_DEFAULT_PASSWORD=admin123
```

## HTACCESS
```bash
# ========================================
# MyStore - Apache Configuration
# ========================================

# PHP тохиргоо
php_value upload_max_filesize 50M
php_value post_max_size 50M
php_value max_execution_time 300
php_value max_input_time 300

# Error reporting (production дээр унтраах)
php_flag display_errors Off
php_flag log_errors On

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Browser caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>

# URL Rewriting (optional - хэрэв SEO friendly URL хийх бол)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /mystore/
    
    # HTTPS руу redirect (production дээр)
    # RewriteCond %{HTTPS} off
    # RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # www-гүй болгох
    # RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
    # RewriteRule ^(.*)$ http://%1/$1 [R=301,L]
</IfModule>

# Directory listing хориглох
Options -Indexes

# Sensitive файлууд хамгаалах
<FilesMatch "\.(htaccess|htpasswd|ini|log|sh|sql)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

&copy; 2025 MYSTORE. Бүх эрх хуулиар хамгаалагдсан.