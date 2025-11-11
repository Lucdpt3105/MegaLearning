# 🎓 MegaLearning - Hệ Thống E-Learning

> **Môn học:** Nhập Môn Công Nghệ Phần Mềm  
> **Năm học:** 2025-2026  
> **Team:** Nhóm 5

---

## 📋 Mục Lục

- [Giới thiệu](#-giới-thiệu)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Cấu hình Database](#-cấu-hình-database)
- [Chạy dự án](#-chạy-dự-án)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [API Documentation](#-api-documentation)
- [Troubleshooting](#-troubleshooting)

---

## 🌟 Giới thiệu

**MegaLearning** là hệ thống E-Learning fullstack với 4 chức năng chính:

1. **📚 Quản lý môn học, tài liệu, đề thi**
2. **💬 Chat nhóm và diễn đàn Q&A với AI 🤖**
3. **📹 Video call học nhóm (WebRTC/Zoom API)**
4. **👥 Phân quyền user: Học viên, Giảng viên, Admin**

### 🆕 **NEW: Chat Realtime với AI Assistant**

Hệ thống chat nhóm realtime tích hợp AI:
- ✅ Chat realtime giữa nhiều người trong cùng một phòng
- ✅ AI Assistant tự động trả lời khi được mention hoặc có câu hỏi
- ✅ Giao diện hiện đại với Tailwind CSS
- ✅ Broadcasting với Pusher (hoặc polling mode nếu chưa config)
- ✅ Tạo/quản lý phòng chat dễ dàng

**🚀 Quick Start:**
1. Truy cập: `http://localhost:8000/chat-demo`
2. Tạo phòng mới hoặc chọn phòng có sẵn
3. Bắt đầu chat! AI sẽ tự động trả lời khi bạn:
   - Mention AI: "AI ơi...", "bot help..."
   - Đặt câu hỏi: "Làm thế nào...?", "Giải thích..."

**⚙️ Cấu hình (Optional):**
- **Pusher realtime**: Thêm `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_ID` vào `.env`
- **AI với OpenAI GPT-3.5**: 
  - **Cách nhanh**: Chạy `setup-openai.bat` và paste API key
  - **Hoặc**: Thêm `OPENAI_API_KEY=sk-...` vào `.env`
  - Test: `php test-openai-simple.php`
  - Start worker: `php artisan queue:work`
- **AI Demo Mode (miễn phí)**: Để `OPENAI_API_KEY` trống, AI sẽ dùng mock responses

📖 **Chi tiết**: Xem `CHAT_GUIDE.md` và `OPENAI_SETUP.md`

---

## 💻 Yêu cầu Hệ thống

Trước khi bắt đầu, hãy đảm bảo máy tính đã cài đặt:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x và **npm**
- **MySQL** >= 8.0 (hoặc MariaDB)
- **Git**

### Kiểm tra version:

```bash
php -v
composer -V
node -v
npm -v
mysql --version
git --version
```

### ⚙️ Cấu hình PHP Extensions (Quan trọng!)

**Nếu cài Composer bị lỗi hoặc `composer install` không hoạt động**, cần bật extension ZIP trong PHP:

1. **Tìm file `php.ini`:**
   ```bash
   php --ini
   ```
   _(Output sẽ hiển thị đường dẫn đến file `php.ini`)_

2. **Mở file `php.ini` bằng Notepad/VS Code**

3. **Tìm dòng sau và xóa dấu `;` ở đầu dòng:**
   ```ini
   ;extension=zip
   ```
   **Sửa thành:**
   ```ini
   extension=zip
   ```

4. **Lưu file và restart terminal**, sau đó thử lại:
   ```bash
   composer -V
   ```

**📌 Note:** Extension `zip` cần thiết để Composer có thể giải nén packages từ Packagist.

---

## 🛠️ Công nghệ Sử dụng

### Backend:
- **Laravel 11** - PHP Framework
- **MySQL** - Database
- **Eloquent ORM** - Database management

### Frontend:
- **Blade Templates** - Laravel template engine
- **Tailwind CSS v4** - Utility-first CSS
- **Vite** - Frontend build tool
- **Alpine.js / Vanilla JS** - JavaScript framework

### Tools:
- **Thunder Client** - API testing (VS Code extension)
- **Laravel Artisan** - CLI commands

---

## 📥 Hướng dẫn Cài đặt

### Bước 1: Clone Repository

```bash
git clone https://github.com/Lucdpt3105/MegaLearning.git
cd MegaLearning
```

### Bước 2: Cài đặt Dependencies

#### Backend (PHP):
```bash
composer install
```

#### Frontend (Node.js):
```bash
npm install
```

### Bước 3: Tạo file Environment

```bash
# Windows (PowerShell)
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### Bước 4: Generate Application Key

```bash
php artisan key:generate
```

---

## 🗄️ Cấu hình Database

### Bước 1: Tạo Database trong MySQL

Mở **MySQL Workbench** hoặc **Command Line**:

```sql
CREATE DATABASE learning3;
```

### Bước 2: Cấu hình `.env`

Mở file `.env` và chỉnh sửa thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=learning3
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

**⚠️ LƯU Ý:**
- Thay `your_password_here` bằng password MySQL của bạn
- Nếu dùng XAMPP/WAMP, password mặc định thường là rỗng (xóa `your_password_here`)

### Bước 3: Chạy Migration và Seed Data

```bash
php artisan migrate:fresh --seed
```

Lệnh này sẽ:
- ✅ Tạo tất cả bảng trong database
- ✅ Tạo sẵn 3 tài khoản demo với roles (Admin, Teacher, Student)

**🎯 Tài khoản demo sau khi seed:**

| Role | Email | Password |
|------|-------|----------|
| 👑 Admin | `admin@megalearning.com` | `password` |
| 👨‍🏫 Teacher | `teacher@megalearning.com` | `password` |
| 🎓 Student | `student@megalearning.com` | `password` |

### Bước 4: Kiểm tra kết nối Database

```bash
php artisan db:show
```

Nếu thành công, bạn sẽ thấy thông tin database như sau:

```
MySQL 8.0.x .................................................. learning3
Database ....................................................... learning3
Host ............................................................. 127.0.0.1:3306
Username ........................................................... root
URL ................................................................ -
Open Connections ................................................... 0
Tables ............................................................. 40+
Total Size ......................................................... 2.5 MB
```

---

## 🚀 Chạy Dự án

### 1. Start Backend Server (Laravel)

Mở **Terminal 1**:

```bash
php artisan serve
```

Server sẽ chạy tại: `http://localhost:8000`

### 2. Start Frontend Build (Vite)

Mở **Terminal 2**:

```bash
npm run dev
```

Vite sẽ compile Tailwind CSS và watch files tự động.

### 3. Truy cập Website

Mở trình duyệt và vào:

- **Admin Dashboard:** `http://localhost:8000/admin`
- **API Base URL:** `http://localhost:8000/api/v1`

---

## � Đồng bộ Database khi Làm việc Nhóm

### ✅ Phương pháp 1: Dùng Laravel Migrations

Laravel đã có sẵn hệ thống migration! Khi ai thêm bảng/sửa schema thì chỉ cần push file migration lên Git.

#### 📝 Workflow: - Ví dụ 

**Người A tạo bảng mới:**

```bash
# Tạo migration
php artisan make:migration create_students_table

# Chạy migration để test
php artisan migrate

# Commit file migration lên Git
git add database/migrations/
git commit -m "feat: Add students table migration"
git push
```

**Người B pull code về:**

```bash
# Pull code từ Git
git pull

# Chạy migration để cập nhật DB
php artisan migrate
```

→ **Database tự động đồng bộ!** Không cần share file `.sql` 

#### 📦 Thêm dữ liệu mẫu (Seeders):

```bash
# Tạo seeder
php artisan make:seeder StudentSeeder

# Chạy seeder để thêm dữ liệu
php artisan db:seed --class=StudentSeeder

# Hoặc chạy tất cả seeders
php artisan db:seed
```

#### 🔄 Reset database và migrate lại (khi cần):

```bash
# Rollback tất cả migrations và migrate lại từ đầu
php artisan migrate:fresh

# Migrate lại + chạy seeders
php artisan migrate:fresh --seed
```

---

### 🗄️ Phương pháp 2: Dùng file SQL (Backup/Restore)

Nếu bạn cần chia sẻ database hiện tại (với dữ liệu thật):

#### Export Database:

```bash
# MySQL Command Line
mysqldump -u root -p learning3 > database/backup_$(date +%Y%m%d).sql

# Hoặc dùng MySQL Workbench: Server → Data Export
```

#### Import Database:

```bash
# Tạo database trước
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS learning3;"

# Import file SQL
mysql -u root -p learning3 < database/learning3.sql
```

---

### ⚠️ Lưu ý quan trọng:

1. **Luôn commit file migration** vào Git, không commit file `.sql` backup vào Git (quá nặng)
2. **Không sửa migration đã chạy**, nếu cần sửa thì tạo migration mới:
   ```bash
   php artisan make:migration add_column_to_students_table
   ```
3. **Seeders** nên chứa dữ liệu mẫu để test, không phải dữ liệu thật
4. **File `.env`** không được commit (đã có trong `.gitignore`), mỗi người tự config local

---

### 🚦 Quy trình làm việc nhóm (Best Practice):

```bash
# Sáng đến pull code mới nhất
git pull

# Chạy migration để sync DB
php artisan migrate

# Code feature mới...

# Trước khi commit, chạy lại migration để test
php artisan migrate:fresh --seed

# Commit và push
git add .
git commit -m "feat: Add new feature"
git push
```

---

## �📁 Cấu trúc Dự án

```
MegaLearning/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/                    # API Controllers
│   │           ├── SubjectController.php
│   │           ├── TopicController.php
│   │           ├── QuestionController.php
│   │           └── ExamController.php
│   └── Models/                         # Eloquent Models
│       ├── Subject.php
│       ├── Topic.php
│       ├── Question.php
│       ├── Answer.php
│       ├── Exam.php
│       └── User.php
│
├── database/
│   ├── learning3.sql                   # Database dump
│   └── migrations/                     # Database migrations
│
├── resources/
│   ├── views/
│   │   ├── admin/                      # Admin Dashboard Views
│   │   │   ├── layout.blade.php
│   │   │   ├── dashboard.blade.php
│   │   │   ├── partials/
│   │   │   │   ├── sidebar.blade.php
│   │   │   │   └── header.blade.php
│   │   │   └── subjects/
│   │   │       ├── index.blade.php
│   │   │       └── create.blade.php
│   │   └── components/                 # Reusable Components
│   ├── css/
│   │   └── app.css                     # Tailwind CSS
│   └── js/
│       └── app.js
│
├── routes/
│   ├── web.php                         # Web Routes (Admin UI)
│   └── api.php                         # API Routes (RESTful)
│
├── public/                             # Public assets
├── storage/                            # File storage
├── .env                                # Environment variables
├── composer.json                       # PHP dependencies
├── package.json                        # Node dependencies
└── vite.config.js                      # Vite configuration
```

---

## 🔌 API Documentation

### Base URL
```
http://localhost:8000/api/v1
```

### Endpoints hiện tại

#### **Subjects API**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/subjects` | Lấy danh sách môn học |
| POST | `/subjects` | Tạo môn học mới |
| GET | `/subjects/{id}` | Xem chi tiết môn học |
| PUT | `/subjects/{id}` | Cập nhật môn học |
| DELETE | `/subjects/{id}` | Xóa môn học |

#### **Topics API**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/topics` | Lấy danh sách chủ đề |
| GET | `/topics?subject_id=1` | Lọc theo môn học |
| POST | `/topics` | Tạo chủ đề mới |
| GET | `/topics/{id}` | Xem chi tiết chủ đề |
| PUT | `/topics/{id}` | Cập nhật chủ đề |
| DELETE | `/topics/{id}` | Xóa chủ đề |

#### **Questions API**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/questions` | Lấy danh sách câu hỏi |
| POST | `/questions` | Tạo câu hỏi + đáp án |
| GET | `/questions/{id}` | Xem chi tiết câu hỏi |
| PUT | `/questions/{id}` | Cập nhật câu hỏi |
| DELETE | `/questions/{id}` | Xóa câu hỏi |

#### **Exams API**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/exams` | Lấy danh sách đề thi |
| POST | `/exams` | Tạo đề thi mới |
| GET | `/exams/{id}` | Xem chi tiết đề thi |
| PUT | `/exams/{id}` | Cập nhật đề thi |
| DELETE | `/exams/{id}` | Xóa đề thi |

### Example Request (POST Subject)

```bash
# Thunder Client / Postman
POST http://localhost:8000/api/v1/subjects
Content-Type: application/json

{
  "subject_name": "Lập Trình Web"
}
```

### Example Response

```json
{
  "success": true,
  "data": {
    "subject_id": 1,
    "subject_name": "Lập Trình Web",
    "topics_count": 0
  },
  "message": "Subject created successfully"
}
```

---

## 🧪 Testing API

### Sử dụng Thunder Client (VS Code)

1. Cài đặt extension **Thunder Client** trong VS Code
2. Click icon ⚡ trên Sidebar
3. Tạo New Request:
   - Method: `GET`
   - URL: `http://localhost:8000/api/v1/subjects`
   - Click **Send**

### Sử dụng cURL (Command Line)

```bash
# GET - Lấy danh sách subjects
curl http://localhost:8000/api/v1/subjects

# POST - Tạo subject mới
curl -X POST http://localhost:8000/api/v1/subjects \
  -H "Content-Type: application/json" \
  -d '{"subject_name":"Lập Trình Web"}'
```

---

## 🐛 Troubleshooting

### Lỗi: "Target class [SubjectController] does not exist"

**Nguyên nhân:** Composer chưa autoload classes

**Giải pháp:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

### Lỗi: "SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'"

**Nguyên nhân:** Sai password MySQL trong `.env`

**Giải pháp:**
1. Mở file `.env`
2. Kiểm tra lại `DB_USERNAME` và `DB_PASSWORD`
3. Clear config cache:
```bash
php artisan config:clear
```

---

### Lỗi: "SQLSTATE[42S02]: Base table or view not found"

**Nguyên nhân:** Database chưa được import

**Giải pháp:**
```bash
# Import lại database
mysql -u root -p learning3 < database/learning3.sql

# Hoặc chạy migrations
php artisan migrate:fresh
```

---

### Lỗi: "Vite manifest not found"

**Nguyên nhân:** Vite chưa chạy hoặc chưa build

**Giải pháp:**
```bash
# Development mode
npm run dev

# Production build
npm run build
```

---

### Lỗi: "Route [api/v1/subjects] not defined"

**Nguyên nhân:** File `bootstrap/app.php` thiếu config API routes

**Giải pháp:**

Mở `bootstrap/app.php` và thêm:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',  // ← Thêm dòng này
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
```

Sau đó clear cache:
```bash
php artisan route:clear
php artisan config:clear
```

---

## 🌿 Git Workflow cho Team

### **Clone Repository**

```bash
git clone https://github.com/Lucdpt3105/MegaLearning.git
cd MegaLearning
```

### **Xem tất cả Branches**

```bash
git branch -a
```

**Output:**
```
* main
  feature/webrtc-video-call
  feature/chat-system
  feature/forum-qna
  feature/ai-integration
```

### **Checkout Branch để làm việc**

```bash
# Làm tính năng WebRTC Video Call
git checkout feature/webrtc-video-call

# Làm tính năng Chat System
git checkout feature/chat-system

# Làm tính năng Forum Q&A
git checkout feature/forum-qna

# Làm tính năng AI Integration
git checkout feature/ai-integration
```

### **Commit và Push Code**

```bash
# 1. Thêm files đã thay đổi
git add .

# 2. Commit với message rõ ràng
git commit -m "Add video call feature with WebRTC"

# 3. Push lên GitHub
git push origin feature/webrtc-video-call
# Thay tên branch tương ứng: feature/chat-system, feature/forum-qna, v.v.
```

### **Pull code mới nhất từ GitHub**

```bash
# Cập nhật branch hiện tại
git pull origin <branch-name>

# Ví dụ:
git pull origin feature/webrtc-video-call
```

### **Merge Branch vào Main (sau khi hoàn thành)**

```bash
# 1. Chuyển về main
git checkout main

# 2. Pull code mới nhất
git pull origin main

# 3. Merge branch feature vào main
git merge feature/webrtc-video-call

# 4. Push lên GitHub
git push origin main
```

### **📋 Phân công Branches cho Team**

| Thành viên | Branch | Nhiệm vụ |
|-----------|--------|----------|
| Person A | `feature/webrtc-video-call` | Video call học nhóm, screen sharing |
| Person B | `feature/chat-system` | Real-time chat, group messaging |
| Person C | `feature/forum-qna` | Diễn đàn Q&A, upvote/downvote |
| Person D | `feature/ai-integration` | AI chatbot, auto-grading |

---

## 📚 Tài liệu Tham khảo

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Tailwind CSS v4](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Git Documentation](https://git-scm.com/doc)

---

## 👥 Team Members

- **Luc Dang** - [GitHub](https://github.com/Lucdpt3105)
- *Thêm tên các thành viên khác ở đây*

---

## 📄 License

This project is for educational purposes only (NMCNPM - 2025).

---

## 🆘 Support

Nếu gặp vấn đề, vui lòng:

1. Kiểm tra phần [Troubleshooting](#-troubleshooting)
2. Tạo issue trên GitHub
3. Liên hệ team members

---

**Happy Coding! 🚀**
