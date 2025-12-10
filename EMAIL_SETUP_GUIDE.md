# Hướng dẫn cấu hình Email để gửi link đặt lại mật khẩu

## Phương án 1: Mailtrap (Khuyên dùng - Dễ nhất, Miễn phí)

### Bước 1: Đăng ký Mailtrap

1. Truy cập: https://mailtrap.io/register/signup
2. Đăng ký tài khoản miễn phí (có thể dùng Google)
3. Sau khi đăng nhập, vào **Email Testing** → **Inboxes** → **My Inbox**
4. Chọn tab **SMTP Settings**
5. Chọn **Laravel 9+** trong dropdown "Integrations"
6. Copy thông tin hiển thị

### Bước 2: Cấu hình .env

Mở file `.env` và thay đổi:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username     # Lấy từ Mailtrap
MAIL_PASSWORD=your-mailtrap-password     # Lấy từ Mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@megalearning.com
MAIL_FROM_NAME="MegaLearning"
```

### Bước 3: Test
```bash
php artisan config:clear
```

**Lưu ý:** Email sẽ được gửi vào inbox Mailtrap, KHÔNG gửi thật tới email người dùng (dùng cho testing).

---

## Phương án 2: Gmail SMTP (Cần App Password)

## Phương án 2: Gmail SMTP (Cần App Password)

### Bước 1: Tạo App Password cho Gmail

1. Đăng nhập vào tài khoản Gmail của bạn
2. Truy cập: https://myaccount.google.com/apppasswords
3. Chọn "Mail" và "Windows Computer" (hoặc Other)
4. Click "Generate"
5. Copy mật khẩu 16 ký tự (dạng: xxxx xxxx xxxx xxxx)

### Bước 2: Cấu hình .env

### Bước 2: Cấu hình .env

Mở file `.env` và thay đổi các giá trị sau:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com          # Email Gmail của bạn
MAIL_PASSWORD=xxxx xxxx xxxx xxxx           # App Password vừa tạo (16 ký tự)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com      # Email Gmail của bạn
MAIL_FROM_NAME="MegaLearning"
```

**Lưu ý:** 
- `MAIL_USERNAME` và `MAIL_FROM_ADDRESS` phải giống nhau
- `MAIL_PASSWORD` là App Password (16 ký tự), KHÔNG phải mật khẩu Gmail thường

### Bước 3: Test

### Bước 3: Test

```bash
php artisan config:clear
```

---

## Phương án 3: SendGrid (Miễn phí 100 email/ngày)

### Bước 1: Đăng ký SendGrid

1. Truy cập: https://signup.sendgrid.com/
2. Đăng ký tài khoản miễn phí
3. Xác thực email
4. Vào **Settings** → **API Keys** → **Create API Key**
5. Đặt tên (vd: "MegaLearning") → Full Access → Create
6. Copy API Key (chỉ hiện 1 lần)

### Bước 2: Cấu hình .env

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key      # API Key vừa copy
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@megalearning.com
MAIL_FROM_NAME="MegaLearning"
```

### Bước 3: Verify Sender

1. Vào **Settings** → **Sender Authentication**
2. Click **Verify a Single Sender**
3. Nhập email của bạn và xác thực

---

## Test gửi email (Dùng cho tất cả phương án)

1. Truy cập: http://localhost:8000/forgot-password
2. Nhập email đã đăng ký
3. Click "Gửi link đặt lại"
4. Kiểm tra hộp thư email

## Email sẽ chứa:

✅ Tiêu đề: "Đặt lại mật khẩu - MegaLearning"
✅ Nội dung tiếng Việt
✅ Nút "Đặt lại mật khẩu" với link hợp lệ 60 phút
✅ Thiết kế đẹp mắt, chuyên nghiệp

## Thông báo hiển thị:

- **Thành công:** "Chúng tôi đã gửi link đặt lại mật khẩu qua email của bạn!"
- **Email không tồn tại:** "Không tìm thấy người dùng với địa chỉ email này."
- **Mật khẩu đã đặt lại:** "Mật khẩu của bạn đã được đặt lại thành công!"

## Troubleshooting

Nếu email không gửi được:

1. **Kiểm tra App Password:** Đảm bảo đã bật 2-Step Verification trong Gmail
2. **Kiểm tra .env:** Username và From Address phải giống nhau
3. **Clear cache:** `php artisan config:clear`
4. **Check logs:** `storage/logs/laravel.log`

## Các file đã được tạo/chỉnh sửa:

✅ `app/Notifications/ResetPasswordNotification.php` - Email notification tiếng Việt
✅ `app/Models/User.php` - Override sendPasswordResetNotification
✅ `app/Http/Controllers/Auth/ForgotPasswordController.php` - Thông báo tiếng Việt
✅ `lang/vi/passwords.php` - Ngôn ngữ tiếng Việt
✅ `lang/vi/validation.php` - Validation tiếng Việt
✅ `.env` - Cấu hình SMTP

## Security Notes:

🔒 App Password chỉ dùng cho ứng dụng này
🔒 Link reset có hiệu lực 60 phút
🔒 Không chia sẻ App Password với ai
