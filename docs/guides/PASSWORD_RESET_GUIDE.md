# PASSWORD RESET - HƯỚNG DẪN ĐƠN GIẢN

## 🚀 Cách 1: Reset nhanh qua Script (RECOMMENDED)

### Reset password cho user bất kỳ:
```bash
php scripts/reset-user-password.php email@example.com newpassword
```

### Ví dụ:
```bash
# Reset password cho admin
php scripts/reset-user-password.php admin@megalearning.com 12345678

# Reset password cho teacher
php scripts/reset-user-password.php teacher@megalearning.com 12345678

# Reset password cho student
php scripts/reset-user-password.php student@megalearning.com 12345678
```

---

## 🌐 Cách 2: Qua giao diện web (Full workflow)

### Bước 1: Truy cập trang quên mật khẩu
```
http://localhost:8000/forgot-password
```

### Bước 2: Nhập email và submit
- Nhập email của user cần reset
- Hệ thống sẽ tạo token trong database

### Bước 3: Lấy token từ database
```bash
php artisan tinker --execute="DB::table('password_reset_tokens')->latest('created_at')->first()"
```

### Bước 4: Truy cập link reset với token
```
http://localhost:8000/reset-password/{TOKEN}?email={EMAIL}
```

Ví dụ:
```
http://localhost:8000/reset-password/abc123xyz?email=student@megalearning.com
```

### Bước 5: Nhập mật khẩu mới
- Nhập mật khẩu mới (tối thiểu 8 ký tự)
- Xác nhận mật khẩu
- Submit

---

## 🛠️ Cách 3: Qua Tinker (Manual)

```bash
php artisan tinker
```

Sau đó gõ:
```php
$user = App\Models\User::where('email', 'student@megalearning.com')->first();
$user->password = Hash::make('12345678');
$user->save();
```

---

## ✅ Test accounts

| Email | Default Password | Role |
|-------|-----------------|------|
| admin@megalearning.com | password | admin |
| teacher@megalearning.com | password | teacher |
| student@megalearning.com | password | student |

---

## 📝 Routes có sẵn

| Method | URL | Mô tả |
|--------|-----|-------|
| GET | /forgot-password | Form quên mật khẩu |
| POST | /forgot-password | Gửi reset link |
| GET | /reset-password/{token} | Form reset mật khẩu |
| POST | /reset-password | Xử lý reset mật khẩu |

---

## 🎯 Lưu ý

1. **Password tối thiểu 8 ký tự**
2. **Token reset có hiệu lực 60 phút** (config trong `config/auth.php`)
3. **Throttle: 60 giây** giữa các lần request reset
4. **Database table**: `password_reset_tokens`

---

## 🔧 Troubleshooting

### Nếu bảng password_reset_tokens chưa có:
```bash
php artisan migrate --path=database/migrations/2025_12_13_142710_create_password_reset_tokens_table.php
```

### Nếu muốn xóa tất cả token cũ:
```bash
php artisan tinker --execute="DB::table('password_reset_tokens')->truncate()"
```

### Kiểm tra user tồn tại:
```bash
php artisan tinker --execute="App\Models\User::where('email', 'student@megalearning.com')->first()"
```

---

## 💡 Tips

- **Cách nhanh nhất**: Dùng `scripts/reset-user-password.php`
- **Demo cho thầy**: Dùng giao diện web để show full workflow
- **Development**: Dùng Tinker để test nhanh
