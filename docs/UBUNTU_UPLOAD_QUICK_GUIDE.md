# Quick Setup - Upload File trên Ubuntu Server

## 🚀 Chạy script tự động

```bash
# Cấp quyền thực thi
chmod +x setup-upload-ubuntu.sh

# Chạy script
./setup-upload-ubuntu.sh
```

## 📋 Hoặc chạy thủ công từng bước

### Bước 1: Tạo symbolic link
```bash
php artisan storage:link
```

### Bước 2: Tạo thư mục và phân quyền
```bash
# Tạo thư mục
mkdir -p storage/app/public/documents/{general,lecture,exam,homework}

# Phân quyền (cần sudo)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Bước 3: Cấu hình PHP (nếu cần)
```bash
# Tìm file php.ini
php --ini | grep "Loaded Configuration File"

# Chỉnh sửa
sudo nano /etc/php/8.1/fpm/php.ini

# Thêm/sửa các dòng:
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
```

### Bước 4: Cấu hình Nginx (nếu dùng)
```bash
sudo nano /etc/nginx/sites-available/your-site

# Thêm trong block server:
client_max_body_size 50M;

# Test và restart
sudo nginx -t
sudo systemctl restart nginx
```

### Bước 5: Restart PHP-FPM
```bash
sudo systemctl restart php8.1-fpm
```

## ✅ Kiểm tra

```bash
# Kiểm tra symbolic link
ls -la public/storage

# Kiểm tra quyền
ls -la storage/app/public

# Test upload qua web
curl -X POST http://your-domain.com/admin/files/store \
  -F "file=@test.pdf" \
  -F "title=Test Document" \
  -F "folder=general"
```

## 🐛 Troubleshooting

### Lỗi Permission Denied
```bash
sudo chown -R www-data:www-data /path/to/MegaLearning
sudo chmod -R 775 storage bootstrap/cache
```

### Lỗi Upload File Too Large
```bash
# Kiểm tra PHP config
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Kiểm tra Nginx config
sudo nginx -T | grep client_max_body_size
```

### Lỗi 500 Internal Server Error
```bash
# Xem log
sudo tail -f storage/logs/laravel.log
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php8.1-fpm.log
```

## 📞 Hỗ trợ thêm

Xem file `UPLOAD_SETUP.md` để biết chi tiết đầy đủ.
