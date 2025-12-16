#!/bin/bash

echo "=================================================="
echo "  Fix Lỗi 413 Request Entity Too Large"
echo "  Ubuntu Server - Nginx/Apache"
echo "=================================================="
echo ""

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Kiểm tra web server nào đang chạy
if systemctl is-active --quiet nginx; then
    WEB_SERVER="nginx"
    echo -e "${GREEN}Phát hiện: Nginx${NC}"
elif systemctl is-active --quiet apache2; then
    WEB_SERVER="apache"
    echo -e "${GREEN}Phát hiện: Apache${NC}"
else
    echo -e "${RED}Không tìm thấy Nginx hoặc Apache đang chạy${NC}"
    exit 1
fi
echo ""

# Fix cho Nginx
if [ "$WEB_SERVER" == "nginx" ]; then
    echo -e "${YELLOW}[1/4] Cấu hình Nginx...${NC}"
    
    # Tìm file config của site
    SITE_CONFIG=$(ls /etc/nginx/sites-available/ | head -n 1)
    CONFIG_PATH="/etc/nginx/sites-available/$SITE_CONFIG"
    
    echo "File config: $CONFIG_PATH"
    
    # Backup config cũ
    sudo cp "$CONFIG_PATH" "$CONFIG_PATH.backup.$(date +%Y%m%d_%H%M%S)"
    echo -e "${GREEN}✓ Đã backup config${NC}"
    
    # Kiểm tra xem đã có client_max_body_size chưa
    if grep -q "client_max_body_size" "$CONFIG_PATH"; then
        echo -e "${YELLOW}⚠ client_max_body_size đã tồn tại, cập nhật...${NC}"
        sudo sed -i 's/client_max_body_size.*$/client_max_body_size 50M;/' "$CONFIG_PATH"
    else
        echo -e "${YELLOW}⚠ Thêm client_max_body_size mới...${NC}"
        # Thêm vào trong block server
        sudo sed -i '/server {/a \    client_max_body_size 50M;' "$CONFIG_PATH"
    fi
    
    # Kiểm tra config
    echo -e "${YELLOW}[2/4] Kiểm tra cấu hình Nginx...${NC}"
    if sudo nginx -t; then
        echo -e "${GREEN}✓ Cấu hình Nginx hợp lệ${NC}"
    else
        echo -e "${RED}✗ Cấu hình Nginx không hợp lệ, khôi phục backup...${NC}"
        sudo cp "$CONFIG_PATH.backup"* "$CONFIG_PATH"
        exit 1
    fi
    echo ""
    
    # Restart Nginx
    echo -e "${YELLOW}[3/4] Restart Nginx...${NC}"
    sudo systemctl restart nginx
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Nginx đã được restart${NC}"
    else
        echo -e "${RED}✗ Lỗi khi restart Nginx${NC}"
        exit 1
    fi
fi

# Fix cho Apache
if [ "$WEB_SERVER" == "apache" ]; then
    echo -e "${YELLOW}[1/4] Cấu hình Apache...${NC}"
    
    # Tạo file .htaccess nếu chưa có
    if [ ! -f ".htaccess" ]; then
        echo "Tạo file .htaccess mới..."
        cat > .htaccess << 'EOF'
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

LimitRequestBody 52428800
EOF
    else
        echo "Cập nhật .htaccess..."
        if ! grep -q "LimitRequestBody" .htaccess; then
            echo "LimitRequestBody 52428800" >> .htaccess
        fi
    fi
    echo -e "${GREEN}✓ Đã cấu hình .htaccess${NC}"
    echo ""
    
    # Restart Apache
    echo -e "${YELLOW}[2/4] Restart Apache...${NC}"
    sudo systemctl restart apache2
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Apache đã được restart${NC}"
    else
        echo -e "${RED}✗ Lỗi khi restart Apache${NC}"
        exit 1
    fi
fi

# Fix PHP
echo ""
echo -e "${YELLOW}[4/4] Cấu hình PHP...${NC}"

PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
PHP_INI_FPM="/etc/php/$PHP_VERSION/fpm/php.ini"
PHP_INI_CLI="/etc/php/$PHP_VERSION/cli/php.ini"

echo "PHP Version: $PHP_VERSION"

# Backup PHP config
if [ -f "$PHP_INI_FPM" ]; then
    sudo cp "$PHP_INI_FPM" "$PHP_INI_FPM.backup.$(date +%Y%m%d_%H%M%S)"
    
    # Update PHP-FPM config
    sudo sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 50M/' "$PHP_INI_FPM"
    sudo sed -i 's/^post_max_size = .*/post_max_size = 50M/' "$PHP_INI_FPM"
    sudo sed -i 's/^max_execution_time = .*/max_execution_time = 300/' "$PHP_INI_FPM"
    sudo sed -i 's/^memory_limit = .*/memory_limit = 256M/' "$PHP_INI_FPM"
    
    echo -e "${GREEN}✓ Đã cập nhật $PHP_INI_FPM${NC}"
    
    # Restart PHP-FPM
    sudo systemctl restart php${PHP_VERSION}-fpm
    echo -e "${GREEN}✓ PHP-FPM đã được restart${NC}"
fi

echo ""
echo "=================================================="
echo -e "${GREEN}Fix lỗi 413 hoàn tất!${NC}"
echo "=================================================="
echo ""
echo "📝 Các giá trị đã được cấu hình:"
echo "  - Nginx/Apache: 50MB"
echo "  - PHP upload_max_filesize: 50MB"
echo "  - PHP post_max_size: 50MB"
echo "  - PHP max_execution_time: 300s"
echo ""
echo "🧪 Kiểm tra:"
if [ "$WEB_SERVER" == "nginx" ]; then
    echo "  sudo nginx -t"
    echo "  sudo systemctl status nginx"
else
    echo "  sudo systemctl status apache2"
fi
echo "  php -i | grep upload_max_filesize"
echo ""
echo "🔄 Thử upload lại file trên web interface"
echo "=================================================="
