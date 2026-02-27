# Hướng dẫn cài đặt chức năng Upload File (Ubuntu Server)

## ✅ Cài đặt trên Ubuntu Server

### 1. Tạo Symbolic Link (QUAN TRỌNG)
```bash
cd /path/to/MegaLearning
php artisan storage:link
```
Lệnh này tạo symbolic link từ `public/storage` → `storage/app/public`

### 2. Tạo thư mục và phân quyền (Ubuntu)
```bash
# Tạo thư mục documents
mkdir -p storage/app/public/documents/{general,lecture,exam,homework}

# Phân quyền cho thư mục storage (www-data là user của web server)
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage

# Phân quyền cho thư mục bootstrap/cache
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 bootstrap/cache

# Nếu dùng user khác (ví dụ: ubuntu), thêm user vào group www-data
sudo usermod -a -G www-data $USER
```

### 3. Kiểm tra symbolic link
```bash
ls -la public/ | grep storage
# Kết quả phải có: lrwxrwxrwx ... storage -> ../storage/app/public
```

### 4. Cấu hình đã hoàn thiện
- ✅ Form upload đã được cập nhật với đầy đủ các trường bắt buộc
- ✅ FileController xử lý upload đúng cách
- ✅ Routes đã được định nghĩa
- ✅ Validation đã được thiết lập

## 📝 Cách sử dụng

### Truy cập trang upload:
```
http://127.0.0.1:8000/admin/files/upload
```

### Các trường cần điền:
1. **Tiêu đề** (bắt buộc) - Tên file/tài liệu
2. **Chọn file** (bắt buộc) - File cần upload (max 50MB)
3. **Môn học** (tùy chọn) - Chọn môn học liên quan
4. **Thư mục** (bắt buộc) - Chọn 1 trong 4 thư mục
5. **Mô tả** (tùy chọn) - Chi tiết về file

### Các loại file được hỗ trợ:
- PDF, DOC, DOCX
- XLS, XLSX
- PPT, PPTX
- ZIP, RAR

### Giới hạn:
- Kích thước tối đa: **50MB** mỗi file
- Upload từng file một (không hỗ trợ multiple upload)

## 🐛 Khắc phục sự cố (Ubuntu Server)

### Lỗi "File không tải lên được"

1. **Kiểm tra symbolic link:**
```bash
ls -la public/storage
# Phải thấy: storage -> ../storage/app/public
```

2. **Kiểm tra quyền thư mục:**
```bash
# Kiểm tra owner và permissions
ls -la storage/app/public

# Sửa quyền nếu cần
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage
```

3. **Kiểm tra dung lượng file (không vượt quá 50MB)**

4. **Kiểm tra file `.env`:**
```env
FILESYSTEM_DISK=local
```

5. **Kiểm tra SELinux (nếu có):**
```bash
# Kiểm tra trạng thái SELinux
getenforce

# Tạm thời tắt nếu đang bật
sudo setenforce 0
```

### 4. Thay đổi giới hạn trong Laravel validation:
File: `app/Http/Controllers/Admin/FileController.php`
```php
'file' => 'required|file|max:51200', // 50MB (giá trị tính bằng KB)
```

### 5. Kiểm tra log errors

```bash
# Log Laravel
tail -f storage/logs/laravel.log

# Log PHP-FPM
sudo tail -f /var/log/php8.1-fpm.log

# Log Nginx
sudo tail -f /var/log/nginx/error.log

# Log Apache
sudo tail -f /var/log/apache2/error.log
```

## 📊 Xem danh sách file đã upload:
```
http://your-domain.come và bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Lỗi "The stream or file could not be opened"

```bash
# Kiểm tra log Laravel
sudo tail -f storage/logs/laravel.log

# Phân quyền cho thư mục logs
sudo chmod -R 775 storage/logs
```

## 🔧 Cấu hình nâng cao (Ubuntu Server)

### 1. Tăng giới hạn upload trong PHP

**Tìm file php.ini:**
```bash
# Tìm file php.ini đang dùng
php --ini | grep "Loaded Configuration File"

# Hoặc với PHP-FPM
sudo nano /etc/php/8.1/fpm/php.ini  # Thay 8.1 bằng version PHP của bạn
```

**Chỉnh sửa các giá trị sau:**
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

**Restart PHP-FPM:**
```bash
sudo systemctl restart php8.1-fpm  # Thay 8.1 bằng version PHP của bạn
```

### 2. Cấu hình Nginx (nếu dùng Nginx)

**Chỉnh sửa Nginx config:**
```bash
sudo nano /etc/nginx/sites-available/megalearning
```

**Thêm vào block server:**
```nginx
server {
    # ... cấu hình khác ...
    
    # Tăng giới hạn upload
    client_max_body_size 50M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeout cho upload
        fastcgi_read_timeout 300;
    }
}
```

**Restart Nginx:**
```bash
sudo nginx -t  # Kiểm tra cấu hình
sudo systemctl restart nginx
```

### 3. Cấu hình Apache (nếu dùng Apache)

**Chỉnh sửa .htaccess hoặc VirtualHost:**
```apache
<IfModule mod_php.c>
    php_value upload_max_filesize 50M
    php_value post_max_size 50M
    php_value max_execution_time 300
    php_value memory_limit 256M
</IfModule>
```

**Restart Apache:**
```bash
sudo systemctl restart apache2
```

### Thay đổi giới hạn trong validation:
File: `app/Http/Controllers/Admin/FileController.php`
```php
'file' => 'required|file|max:51200', // 50MB (giá trị tính bằng KB)
```

## 📊 Xem danh sách file đã upload:
```
http://127.0.0.1:8000/admin/files
```

## 🎯 Tính năng
- ✅ Upload file với validation đầy đủ
- ✅ Phân loại theo thư mục
- ✅ Gán môn học
- ✅ Tự động fill tiêu đề từ tên file
- ✅ Hiển thị thông báo thành công/lỗi
- ✅ Nút quay về danh sách
- ✅ Giao diện admin đẹp mắt

## ✨ Cải tiến đã thực hiện
1. Bổ sung đầy đủ form fields (title, folder)
2. Loại bỏ thuộc tính `multiple` (chỉ upload 1 file/lần)
3. Thêm validation rõ ràng
4. Auto-fill title từ filename
5. Hiển thị lỗi validation đầy đủ
6. Thêm icon và styling đẹp hơn
7. Responsive design
