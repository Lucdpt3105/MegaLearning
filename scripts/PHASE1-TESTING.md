# ✅ PHASE 1 TESTING CHECKLIST

## 🔧 Automated Tests - PASSED ✅

### Database Tests
- [x] Users table có đầy đủ 13 columns
- [x] Các field mới: avatar, phone, bio, last_login_at, is_locked ✓
- [x] User model có fillable fields đúng
- [x] Storage symlink đã được tạo

### Roles & Permissions Tests
- [x] 4 Roles tồn tại: admin, teacher, student, ai
- [x] Admin role: 15 permissions
- [x] Teacher role: 14 permissions  
- [x] Student role: 7 permissions
- [x] Total: 29 permissions trong hệ thống

### Routes Tests
- [x] profile.edit - GET /profile/edit
- [x] profile.update - PUT /profile/update
- [x] profile.password - GET /profile/password
- [x] profile.password.update - PUT /profile/password
- [x] profile.avatar.delete - DELETE /profile/avatar
- [x] password.request - GET /forgot-password
- [x] password.email - POST /forgot-password
- [x] password.reset - GET /reset-password/{token}
- [x] password.update - POST /reset-password

### Existing Users
- [x] User #1: admin@megalearning.com (admin role)
- [x] User #2: teacher@megalearning.com (teacher role)
- [x] User #3: student@megalearning.com (student role)
- [x] User #4: ai@megalearning.com (ai user)
- [x] User #5: admin@test.com (admin role)

---

## 🌐 MANUAL BROWSER TESTS - TODO

Server running at: **http://127.0.0.1:8000**

### Test 1: Forgot Password Flow
**URL**: http://127.0.0.1:8000/forgot-password

- [ ] Trang forgot password hiển thị đúng UI
- [ ] Form có field email
- [ ] Button "Send Reset Link" hoạt động
- [ ] Link "Back to Login" hoạt động
- [ ] Nhập email hợp lệ → Hiển thị thông báo success
- [ ] Nhập email không tồn tại → Hiển thị lỗi
- [ ] (Optional) Check email nhận được reset link

### Test 2: Login Page
**URL**: http://127.0.0.1:8000/login

- [ ] Link "Quên mật khẩu?" hiển thị
- [ ] Click link "Quên mật khẩu?" → Chuyển đến /forgot-password
- [ ] Login với: admin@megalearning.com / password
- [ ] Sau login → Redirect đúng trang

### Test 3: Profile Management
**URL**: http://127.0.0.1:8000/profile/edit (Cần login trước)

**Prerequisites**: Login với admin@megalearning.com / password

#### 3a. Edit Profile
- [ ] Trang edit profile hiển thị đúng
- [ ] Sidebar có link "My Profile" (active)
- [ ] Form hiển thị thông tin user hiện tại
- [ ] Avatar preview hiển thị (default = chữ cái đầu tên)
- [ ] Fields: name, email, phone, bio đều có
- [ ] Upload avatar mới:
  - [ ] Chọn file JPG/PNG
  - [ ] Preview hiển thị ngay
  - [ ] Click "Save Changes"
  - [ ] Avatar mới lưu thành công
  - [ ] Reload page → Avatar mới vẫn hiển thị
- [ ] Sửa name, email, phone, bio
- [ ] Click "Save Changes" → Thông báo success
- [ ] Reload page → Dữ liệu mới hiển thị đúng

#### 3b. Remove Avatar
- [ ] Có avatar → Button "Remove photo" hiển thị
- [ ] Click "Remove photo"
- [ ] Avatar bị xóa, quay về default
- [ ] Database: avatar field = null

#### 3c. Change Password
- [ ] Click link "Change Password" trong sidebar
- [ ] Trang change password hiển thị
- [ ] Form có 3 fields:
  - [ ] Current Password
  - [ ] New Password
  - [ ] Confirm New Password
- [ ] Security tips hiển thị
- [ ] Test validation:
  - [ ] Current password sai → Error
  - [ ] New password < 8 ký tự → Error
  - [ ] Confirm password không khớp → Error
- [ ] Nhập đúng → Password đổi thành công
- [ ] Logout và login lại với password mới → OK

### Test 4: Logout Functionality
**Prerequisites**: Đang login

- [ ] Sidebar có button "Logout" (icon log out)
- [ ] Click "Logout"
- [ ] Session bị xóa
- [ ] Redirect về trang login
- [ ] Truy cập /profile/edit → Redirect về login

### Test 5: Sidebar Navigation
- [ ] Link "My Profile" có icon user
- [ ] Link "My Profile" active khi ở /profile/*
- [ ] Button "Logout" có icon log out
- [ ] Button "Logout" có hover effect màu đỏ

### Test 6: Responsive Design
- [ ] Mobile view: Sidebar responsive
- [ ] Tablet view: Form layout OK
- [ ] Desktop view: 2 columns (sidebar + form)

---

## 🐛 BUG TRACKING

### Issues Found
*(Ghi lại bug phát hiện ở đây)*

| # | Page | Description | Status |
|---|------|-------------|--------|
| - | - | - | - |

---

## 📝 TEST CREDENTIALS

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@megalearning.com | password |
| Teacher | teacher@megalearning.com | password |
| Student | student@megalearning.com | password |

---

## ✅ ACCEPTANCE CRITERIA

### Phase 1 is COMPLETE when:
- [x] All automated tests pass
- [ ] All manual browser tests pass
- [ ] No critical bugs found
- [ ] All UC-GLOBAL use cases work:
  - [ ] UC-GLOBAL-002: Logout ✓
  - [ ] UC-GLOBAL-003: Forgot Password ✓
  - [ ] UC-GLOBAL-004: Profile Management ✓

---

**Tester**: _____________  
**Date**: November 12, 2025  
**Build**: feature/chat-system branch
