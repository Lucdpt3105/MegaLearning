# Fix Lỗi 413 Request Entity Too Large

## 🚨 Nguyên nhân
Lỗi 413 xảy ra khi web server (Nginx/Apache) chặn file upload vì vượt quá giới hạn cho phép.

## 🚀 Chạy script tự động (Khuyến nghị)

```bash
chmod +x fix-413-error.sh
./fix-413-error.sh
```

## 📋 Hoặc fix thủ công

### A. Nếu dùng NGINX

#### 1. Tìm file config
```bash
# Thường là file default hoặc tên domain
ls /etc/nginx/sites-available/

# Ví dụ: megalearning, default, your-domain.com
```

#### 2. Chỉnh sửa file config
```bash
sudo nano /etc/nginx/sites-available/default  # Hoặc tên file của bạn
```

#### 3. Thêm hoặc sửa dòng này trong block `server { }`
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/MegaLearning/public;

    # THÊM DÒNG NÀY
    client_max_body_size 50M;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        # THÊM TIMEOUT CHO UPLOAD
        fastcgi_read_timeout 300;
    }
}
```

#### 4. Kiểm tra và restart
```bash
# Kiểm tra config
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx

# Kiểm tra status
sudo systemctl status nginx
```

### B. Nếu dùng APACHE

#### 1. Chỉnh sửa .htaccess
```bash
nano /path/to/MegaLearning/.htaccess
```

#### 2. Thêm vào .htaccess
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

<IfModule mod_php.c>
    php_value upload_max_filesize 50M
    php_value post_max_size 50M
    php_value max_execution_time 300
    php_value memory_limit 256M
</IfModule>

# Giới hạn kích thước request (50MB = 52428800 bytes)
LimitRequestBody 52428800
```

#### 3. Hoặc sửa VirtualHost config
```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

Thêm:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/MegaLearning/public

    <Directory /path/to/MegaLearning/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        
        # Giới hạn upload
        LimitRequestBody 52428800
        
        php_value upload_max_filesize 50M
        php_value post_max_size 50M
        php_value max_execution_time 300
    </Directory>
</VirtualHost>
```

#### 4. Restart Apache
```bash
sudo systemctl restart apache2
sudo systemctl status apache2
```

### C. Fix PHP (Bắt buộc cho cả Nginx và Apache)

#### 1. Tìm file php.ini
```bash
php --ini | grep "Loaded Configuration File"

# Hoặc trực tiếp
ls /etc/php/*/fpm/php.ini
ls /etc/php/*/cli/php.ini
```

#### 2. Chỉnh sửa php.ini
```bash
# Thay 8.1 bằng version PHP của bạn
sudo nano /etc/php/8.1/fpm/php.ini
```

#### 3. Tìm và sửa các dòng sau:
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
max_input_time = 300
```

#### 4. Restart PHP-FPM
```bash
# Thay 8.1 bằng version PHP của bạn
sudo systemctl restart php8.1-fpm

# Kiểm tra
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

## ✅ Kiểm tra sau khi fix

### 1. Kiểm tra Nginx/Apache
```bash
# Nginx
sudo nginx -t
curl -I http://your-domain.com

# Apache
sudo apache2ctl -t
```

### 2. Kiểm tra PHP
```bash
php -i | grep -E "upload_max_filesize|post_max_size"
```

### 3. Test upload qua terminal
```bash
curl -X POST http://your-domain.com/admin/files/store \
  -H "Cookie: your-session-cookie" \
  -F "file=@/path/to/large-file.pdf" \
  -F "title=Test File" \
  -F "folder=general" \
  -F "_token=your-csrf-token"
```

### 4. Kiểm tra log nếu vẫn lỗi
```bash
# Log Nginx
sudo tail -f /var/log/nginx/error.log

# Log Apache
sudo tail -f /var/log/apache2/error.log

# Log PHP-FPM
sudo tail -f /var/log/php8.1-fpm.log

# Log Laravel
tail -f storage/logs/laravel.log
```

## 🎯 Giải thích giá trị

- **50M** = 50 Megabytes
- **52428800** = 50MB tính bằng bytes (50 × 1024 × 1024)
- **300** = 300 giây (5 phút) - thời gian timeout

## 🔄 Nếu vẫn gặp lỗi

### 1. Restart tất cả services
```bash
sudo systemctl restart nginx      # hoặc apache2
sudo systemctl restart php8.1-fpm
```

### 2. Clear cache Laravel
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. Kiểm tra firewall/proxy
Nếu đứng sau Cloudflare hoặc load balancer, cần cấu hình ở đó nữa.

### 4. Tăng giới hạn lên 100M
Nếu cần upload file lớn hơn 50MB:
- Nginx: `client_max_body_size 100M;`
- PHP: `upload_max_filesize = 100M` và `post_max_size = 100M`
- Apache: `LimitRequestBody 104857600` (100MB)

## 📞 Debug commands hữu ích

```bash
# Xem tất cả PHP settings
php -i | less

# Xem Nginx config đang dùng
sudo nginx -T | grep client_max_body_size

# Test Nginx config
sudo nginx -t -c /etc/nginx/nginx.conf

# Xem process đang chạy
ps aux | grep nginx
ps aux | grep php-fpm
```
