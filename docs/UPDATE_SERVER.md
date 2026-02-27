# Cập nhật code lên Server Ubuntu

## Lỗi hiện tại:
```
ArgumentCountError: Too few arguments to function 
App\Services\AdminNotificationService::notifyNewDocumentUploaded(), 
1 passed but 2 expected
```

## Nguyên nhân:
- Code trên server chưa được cập nhật
- Method `notifyNewDocumentUploaded()` đã được sửa để chỉ cần 1 parameter
- Cần đẩy code mới lên server và clear cache

## Các bước thực hiện trên Server:

### 1. Backup trước khi update (khuyến nghị):
```bash
cd /var/www/MegaLearning
cp -r app app_backup_$(date +%Y%m%d_%H%M%S)
```

### 2. Pull code mới từ Git:
```bash
git pull origin main
# hoặc branch bạn đang dùng
```

### 3. Clear cache Laravel:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 4. Restart services:
```bash
# Nếu dùng PHP-FPM
sudo systemctl restart php8.1-fpm

# Nếu dùng Apache
sudo systemctl restart apache2

# Nếu dùng Nginx
sudo systemctl restart nginx
```

### 5. Kiểm tra lại:
```bash
# Test xem file đã được cập nhật chưa
grep -A 5 "notifyNewDocumentUploaded" app/Services/AdminNotificationService.php

# Nên thấy: public function notifyNewDocumentUploaded($document)
# KHÔNG phải: public function notifyNewDocumentUploaded($document, $uploader)
```

## Nếu không dùng Git trên server:

### Cách 1: Upload file trực tiếp
```bash
# Trên máy local (Windows), tạo file zip
# Rồi upload lên server và unzip
```

### Cách 2: Sử dụng SCP/SFTP
```bash
# Trên máy local
scp app/Services/AdminNotificationService.php user@server:/var/www/MegaLearning/app/Services/

# Sau đó clear cache trên server
ssh user@server "cd /var/www/MegaLearning && php artisan cache:clear && sudo systemctl restart php8.1-fpm"
```

## Kiểm tra sau khi update:

1. Truy cập: http://your-server/admin/files/upload
2. Upload một file test
3. Không còn thấy lỗi ArgumentCountError
4. Admin nhận được thông báo về file mới

## Troubleshooting:

### Nếu vẫn còn lỗi:
```bash
# Kiểm tra opcache
php -r "var_dump(opcache_get_status());"

# Clear opcache
php -r "opcache_reset();"

# Hoặc restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### Kiểm tra permissions:
```bash
cd /var/www/MegaLearning
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```
