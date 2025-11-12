# 🛠️ Scripts & Utilities

Thư mục này chứa các script tiện ích và file test cho dự án MegaLearning.

## 📂 Cấu trúc

### Setup Scripts (`.bat`)
- `setup-gemini.bat` - Setup Gemini AI API key
- `chat-start.bat` - Khởi động chat server nhanh
- `check-security.bat` - Kiểm tra bảo mật
- `create-users.bat` - Tạo user demo

### Test Scripts
- `test-gemini.php` - Test Gemini AI API connection
- `test-chat-direct.php` - Test chat API trực tiếp
- `test-chat-api.ps1` - PowerShell test chat API
- `test-chat-no-auth.bat` - Test chat không cần auth
- `test-openai.bat` - Test OpenAI API (legacy)

### Utility Scripts
- `list-gemini-models.php` - Liệt kê các Gemini models có sẵn

## 🚀 Sử dụng

### Setup Gemini AI
```bash
cd scripts
setup-gemini.bat
```

### Test Chat System
```bash
cd scripts
test-chat-direct.php
```

### Khởi động Chat
```bash
cd scripts
chat-start.bat
```

## 📝 Lưu ý

- Các script `.bat` chỉ chạy trên Windows
- Các script `.ps1` yêu cầu PowerShell
- Các file `.php` có thể chạy trực tiếp: `php script-name.php`
