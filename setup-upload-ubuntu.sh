#!/bin/bash

echo "=================================================="
echo "  Setup Upload File Feature - MegaLearning"
echo "  Ubuntu Server Configuration"
echo "=================================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Get the directory where script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo -e "${YELLOW}[1/6] Tạo symbolic link...${NC}"
php artisan storage:link
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Symbolic link đã được tạo${NC}"
else
    echo -e "${RED}✗ Lỗi khi tạo symbolic link${NC}"
fi
echo ""

echo -e "${YELLOW}[2/6] Tạo thư mục documents...${NC}"
mkdir -p storage/app/public/documents/{general,lecture,exam,homework}
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Thư mục documents đã được tạo${NC}"
    echo "  - storage/app/public/documents/general"
    echo "  - storage/app/public/documents/lecture"
    echo "  - storage/app/public/documents/exam"
    echo "  - storage/app/public/documents/homework"
else
    echo -e "${RED}✗ Lỗi khi tạo thư mục${NC}"
fi
echo ""

echo -e "${YELLOW}[3/6] Phân quyền cho thư mục storage...${NC}"
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Quyền đã được cấp cho storage${NC}"
else
    echo -e "${RED}✗ Lỗi khi phân quyền (cần sudo)${NC}"
fi
echo ""

echo -e "${YELLOW}[4/6] Phân quyền cho thư mục bootstrap/cache...${NC}"
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 bootstrap/cache
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Quyền đã được cấp cho bootstrap/cache${NC}"
else
    echo -e "${RED}✗ Lỗi khi phân quyền bootstrap/cache${NC}"
fi
echo ""

echo -e "${YELLOW}[5/6] Kiểm tra symbolic link...${NC}"
if [ -L "public/storage" ]; then
    TARGET=$(readlink public/storage)
    echo -e "${GREEN}✓ Symbolic link tồn tại: public/storage -> $TARGET${NC}"
else
    echo -e "${RED}✗ Symbolic link không tồn tại!${NC}"
fi
echo ""

echo -e "${YELLOW}[6/6] Kiểm tra cấu hình PHP...${NC}"
PHP_INI=$(php --ini | grep "Loaded Configuration File" | cut -d':' -f2 | xargs)
echo "PHP Config: $PHP_INI"

UPLOAD_MAX=$(php -r "echo ini_get('upload_max_filesize');")
POST_MAX=$(php -r "echo ini_get('post_max_size');")
echo "upload_max_filesize: $UPLOAD_MAX"
echo "post_max_size: $POST_MAX"

if [[ "$UPLOAD_MAX" == "50M" ]] || [[ "$UPLOAD_MAX" == "50" ]]; then
    echo -e "${GREEN}✓ PHP upload limit đã được cấu hình${NC}"
else
    echo -e "${YELLOW}⚠ Cần tăng upload_max_filesize lên 50M trong $PHP_INI${NC}"
fi
echo ""

echo "=================================================="
echo -e "${GREEN}Cài đặt hoàn tất!${NC}"
echo "=================================================="
echo ""
echo "📝 Các bước tiếp theo:"
echo "1. Kiểm tra và chỉnh sửa PHP config nếu cần:"
echo "   sudo nano $PHP_INI"
echo ""
echo "2. Restart PHP-FPM:"
echo "   sudo systemctl restart php8.1-fpm  # Thay 8.1 bằng PHP version của bạn"
echo ""
echo "3. Restart web server:"
echo "   sudo systemctl restart nginx   # Nếu dùng Nginx"
echo "   sudo systemctl restart apache2 # Nếu dùng Apache"
echo ""
echo "4. Truy cập trang upload:"
echo "   http://your-domain.com/admin/files/upload"
echo ""
echo "=================================================="
