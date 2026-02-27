# MegaLearning - Nền tảng E-Learning Hiện đại

<div align="center">

![MegaLearning Banner](https://i.ibb.co/1fgWd83s/ladning.png)

**Nền tảng học trực tuyến thế hệ mới với Video Call, AI Assistant, Quản lý Thi cử và Cộng tác Real-time**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

[Demo](#-screenshots) • [Tính năng](#-tính-năng-chính) • [Tech Stack](#-tech-stack) • [Cài đặt](#-cài-đặt)

</div>

---

## 📖 Giới thiệu

**MegaLearning** là nền tảng e-learning toàn diện được xây dựng với Laravel 12, tích hợp nhiều công nghệ hiện đại nhằm mang lại trải nghiệm học tập trực tuyến tốt nhất cho học sinh, giáo viên và quản trị viên.

### ✨ Điểm nổi bật

- 🎥 **Video Call** tích hợp Zoom API & Jitsi Meet
- 🤖 **AI Assistant** sử dụng Google Gemini AI miễn phí
- 📝 **Quản lý thi cử** tự động chấm điểm
- 💬 **Chat Real-time** với Laravel Echo & Pusher
- 🔐 **Phân quyền** đa vai trò (Admin/Teacher/Student)

---

## 📸 Screenshots

### 🏠 Trang chủ & Dashboard
![Trang chủ](https://i.ibb.co/5gYgfrtm/trangchu.png)

---

### 🔐 Đăng nhập / Đăng ký
![Login Page](https://i.ibb.co/3KZrRFD/dn.png)

---

### 👤 Thông tin cá nhân
![Profile](https://i.ibb.co/G3CctXbS/infochanges.png)

---

### 📚 Quản lý Môn học
![Môn học](https://i.ibb.co/MkmWGwBp/monhoc.png)

---

### 📝 Hệ thống Thi cử & Kiểm tra

| Làm bài kiểm tra | Đề thi tự động |
|------------------|----------------|
| ![Kiểm tra](https://i.ibb.co/fdqqXKmy/kt.png) | ![Đề thi auto](https://i.ibb.co/dJBQygSy/dethiauto.png) |

---

### ✅ Chấm điểm & Quản lý câu hỏi

| Chấm bài | Quản lý câu hỏi |
|----------|-----------------|
| ![Chấm bài](https://i.ibb.co/3y3TtzvW/chambai.png) | ![Câu hỏi](https://i.ibb.co/Xf5CSNPH/questions.png) |

---

### 📁 Tài liệu học tập
![Tài liệu](https://i.ibb.co/xSFNs6ct/tailieu.png)

---

### 📅 Thời khóa biểu
![Thời khóa biểu](https://i.ibb.co/gZY2VzjB/tkb.png)

---

### 💬 Diễn đàn Q&A
![Forum](https://i.ibb.co/QvnZcQNS/forum.png)

---

## 🚀 Tính năng chính

### 🎓 Học tập & Thi cử

| Tính năng | Mô tả |
|-----------|-------|
| 📝 **Quản lý bài thi** | Tạo, chỉnh sửa, xóa bài thi với nhiều loại câu hỏi |
| ✅ **Tự động chấm điểm** | Chấm điểm tự động cho trắc nghiệm, đúng/sai |
| ⏱️ **Giới hạn thời gian** | Countdown timer, auto-submit khi hết giờ |
| 🔀 **Ngẫu nhiên hóa** | Xáo trộn câu hỏi và đáp án để chống gian lận |
| 📊 **Thống kê kết quả** | Biểu đồ, báo cáo chi tiết theo lớp/học sinh |
| 🏆 **Bảng xếp hạng** | Xếp hạng học sinh theo điểm số |

### 📹 Giao tiếp & Cộng tác

| Tính năng | Mô tả |
|-----------|-------|
| 🎥 **Video Call** | Tích hợp Zoom API + Jitsi Meet backup |
| 💬 **Chat Real-time** | Nhắn tin 1-1, nhóm với Laravel Echo |
| 🤖 **AI Assistant** | Trợ lý AI Google Gemini hỗ trợ học tập |
| 📢 **Thông báo** | Push notifications real-time |
| 💬 **Diễn đàn Q&A** | Hỏi đáp kiểu Stack Overflow với upvote/downvote |

### 🔐 Quản trị & Bảo mật

| Tính năng | Mô tả |
|-----------|-------|
| 👥 **Phân quyền RBAC** | 3 vai trò: Admin, Teacher, Student |
| 🔑 **Xác thực API** | Laravel Sanctum token-based auth |
| 🔒 **Bảo mật thi** | Chống gian lận, full-screen mode |
| 📧 **Reset Password** | Email-based + CLI tool backup |
| 📋 **Quản lý điểm danh** | Điểm danh lớp học tự động |

### 📚 Nội dung & Tài liệu

| Tính năng | Mô tả |
|-----------|-------|
| 📁 **Thư viện tài liệu** | Upload, chia sẻ tài liệu học tập |
| 📂 **Quản lý môn học** | Tổ chức theo cấu trúc phân cấp |
| 📊 **Import/Export Excel** | Nhập xuất dữ liệu hàng loạt |
| ☁️ **Google Drive** | Tích hợp lưu trữ cloud |

---

## 🛠 Tech Stack

### Backend

| Công nghệ | Version | Mô tả |
|-----------|---------|-------|
| ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) | 12.x | PHP Framework chính |
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) | 8.2+ | Ngôn ngữ server-side |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) | 8.0+ | Cơ sở dữ liệu |
| ![Sanctum](https://img.shields.io/badge/Sanctum-FF2D20?style=flat-square&logo=laravel&logoColor=white) | 4.2 | API Authentication |

### Frontend

| Công nghệ | Version | Mô tả |
|-----------|---------|-------|
| ![Tailwind](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white) | 4.x | CSS Framework |
| ![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white) | 7.x | Build Tool |
| ![Blade](https://img.shields.io/badge/Blade-FF2D20?style=flat-square&logo=laravel&logoColor=white) | - | Templating Engine |
| ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black) | ES6+ | Client-side scripting |

### Real-time & APIs

| Công nghệ | Version | Mô tả |
|-----------|---------|-------|
| ![Pusher](https://img.shields.io/badge/Pusher-300D4F?style=flat-square&logo=pusher&logoColor=white) | 8.x | WebSocket broadcasting |
| ![Laravel Echo](https://img.shields.io/badge/Echo-FF2D20?style=flat-square&logo=laravel&logoColor=white) | 1.16 | Real-time events |
| ![Zoom](https://img.shields.io/badge/Zoom_API-2D8CFF?style=flat-square&logo=zoom&logoColor=white) | - | Video conferencing |
| ![Gemini](https://img.shields.io/badge/Google_Gemini-4285F4?style=flat-square&logo=google&logoColor=white) | - | AI Assistant |

### Packages & Libraries

| Package | Mục đích |
|---------|----------|
| **Spatie Permission** | Role-Based Access Control (RBAC) |
| **Maatwebsite Excel** | Import/Export Excel & CSV |
| **Laravel Telescope** | Debug & Monitoring |
| **Google Drive Ext** | Cloud Storage Integration |
| **PHPSpreadsheet** | Xử lý file Excel |

### DevOps & Tools

| Công cụ | Mục đích |
|---------|----------|
| ![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white) | Containerization |
| ![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white) | Version Control |
| ![PHPUnit](https://img.shields.io/badge/PHPUnit-3775A9?style=flat-square&logo=php&logoColor=white) | Unit Testing |
| ![Dusk](https://img.shields.io/badge/Laravel_Dusk-FF2D20?style=flat-square&logo=laravel&logoColor=white) | Browser Testing |

---

## 🏗 Kiến trúc hệ thống

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT SIDE                          │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   Browser   │  │   Mobile    │  │    API Clients      │  │
│  │  (Blade +   │  │  (Future)   │  │  (Sanctum Token)    │  │
│  │  Tailwind)  │  │             │  │                     │  │
│  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘  │
└─────────┼────────────────┼───────────────────┼──────────────┘
          │                │                   │
          ▼                ▼                   ▼
┌─────────────────────────────────────────────────────────────┐
│                     LARAVEL APPLICATION                     │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                    ROUTES                            │    │
│  │   web.php (Views)  │  api.php (REST API)            │    │
│  └─────────────────────┬───────────────────────────────┘    │
│                        ▼                                    │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                  MIDDLEWARE                          │    │
│  │  Auth │ Role │ Permission │ Sanctum │ CORS          │    │
│  └─────────────────────┬───────────────────────────────┘    │
│                        ▼                                    │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                 CONTROLLERS                          │    │
│  │  Admin │ Teacher │ Student │ API Controllers        │    │
│  └─────────────────────┬───────────────────────────────┘    │
│                        ▼                                    │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                   SERVICES                           │    │
│  │  AIService │ ZoomService │ ExamService │ etc.       │    │
│  └─────────────────────┬───────────────────────────────┘    │
│                        ▼                                    │
│  ┌─────────────────────────────────────────────────────┐    │
│  │                    MODELS                            │    │
│  │  User │ Exam │ Question │ ChatRoom │ VideoCall      │    │
│  └─────────────────────┬───────────────────────────────┘    │
└─────────────────────────┼───────────────────────────────────┘
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │    MySQL     │  │    Redis     │  │ Google Drive │       │
│  │  (Database)  │  │   (Queue)    │  │   (Storage)  │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
└─────────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                  EXTERNAL SERVICES                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐    │
│  │   Zoom   │  │  Jitsi   │  │  Gemini  │  │  Pusher  │    │
│  │   API    │  │   Meet   │  │    AI    │  │WebSocket │    │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘    │
└─────────────────────────────────────────────────────────────┘
```

---

## 💻 Cài đặt

### Yêu cầu hệ thống

- PHP >= 8.2
- MySQL >= 8.0
- Composer >= 2.0
- Node.js >= 18.x
- npm >= 9.x

### Cài đặt nhanh

```bash
# Clone repository
git clone https://github.com/Lucdpt3105/MegaLearning.git
cd MegaLearning

# Cài đặt dependencies
composer install
npm install

# Cấu hình environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Build assets
npm run build

# Chạy server
php artisan serve
```

### Tài khoản demo

| Email | Password | Vai trò |
|-------|----------|---------|
| admin@megalearning.com | password | Admin |
| teacher@megalearning.com | password | Teacher |
| student@megalearning.com | password | Student |

---

## 📊 Database Schema

<details>
<summary>📋 Các bảng chính</summary>

| Bảng | Mô tả |
|------|-------|
| `users` | Thông tin người dùng |
| `exams` | Bài thi |
| `questions` | Câu hỏi |
| `answers` | Đáp án |
| `submissions` | Bài làm |
| `chat_rooms` | Phòng chat |
| `chat_messages` | Tin nhắn |
| `video_calls` | Cuộc gọi video |
| `subjects` | Môn học |
| `documents` | Tài liệu |

</details>

---

## 🧪 Testing

```bash
# Chạy tất cả tests
php artisan test

# Chạy feature tests
php artisan test --testsuite=Feature

# Chạy browser tests
php artisan dusk
```


[![Star](https://img.shields.io/github/stars/Lucdpt3105/MegaLearning?style=social)](https://github.com/Lucdpt3105/MegaLearning)

[⬆ Về đầu trang](#-megalearning---nền-tảng-e-learning-hiện-đại)

</div>
