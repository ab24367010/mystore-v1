COMPOSER
```bash
composer -V
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
```

GITHUB
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

.env
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