# 📚 MegaLearning - E-Learning Platform

> Modern e-learning platform built with Laravel 12, featuring video conferencing, AI chat assistant, exam management, and real-time collaboration.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)](#)

---

## 📖 Table of Contents

- [Quick Start](#-quick-start)
- [Features](#-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage Guide](#-usage-guide)
- [API Documentation](#-api-documentation)
- [Testing](#-testing)
- [Scripts & Tools](#-scripts--tools)
- [Troubleshooting](#-troubleshooting)
- [Project Structure](#-project-structure)
- [Additional Documentation](#-additional-documentation)

---

## 🚀 Quick Start

### One-Command Setup
```bash
# Clone repository
git clone https://github.com/Lucdpt3105/MegaLearning.git
cd MegaLearning

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Start development servers
composer dev
# 3. Test:
```

**Access the application:**
- Web: http://localhost:8000
- Admin: http://localhost:8000/admin
- Teacher: http://localhost:8000/teacher
- Student: http://localhost:8000/student

### Default Test Accounts
| Email | Password | Role |
|-------|----------|------|
| admin@megalearning.com | password | admin |
| teacher@megalearning.com | password | teacher |
| student@megalearning.com | password | student |

---

## ✨ Features

### 🎓 Core Learning Features
- **Exam Management** - Create, take, auto-grade exams with multiple question types
- **Subject & Topic Management** - Organize courses with hierarchical structure
- **Document Library** - Upload and share learning materials
- **Forum & Q&A** - Stack Overflow-style forum with voting system
- **Student Rankings** - Leaderboards and achievement tracking
- **Attendance Tracking** - Class attendance management

### 📹 Communication & Collaboration
- **Video Conferencing** - Zoom API integration + Jitsi fallback
- **Real-time Chat** - Group chat, private messages, AI assistant
- **AI Chat Assistant** - Google Gemini AI-powered Q&A (FREE)
- **Notifications** - Real-time notifications for all activities

### 🔐 Security & Administration
- **Role-Based Access Control** - Admin, Teacher, Student roles
- **Password Reset** - Email-based + CLI tool
- **Exam Security** - Anti-cheating measures, time limits, randomization
- **API Authentication** - Sanctum token-based auth

### 🛠️ Developer Features
- **RESTful API** - Full API with Sanctum authentication
- **Testing Suite** - 36+ test cases (PHPUnit + Dusk)
- **API Testing** - Thunder Client integration
- **Database Seeding** - Demo data generators
- **Helper Scripts** - 20+ utility scripts

---

## 💻 System Requirements

- **PHP** >= 8.2
- **MySQL** >= 8.0 or MariaDB >= 10.3
- **Composer** >= 2.0
- **Node.js** >= 18.x & npm
- **Git** (for version control)

**Optional:**
- **ChromeDriver** (for Dusk browser tests)
- **Zoom Account** (for video conferencing)
- **Google Gemini API Key** (for AI assistant)

---

## 📦 Installation

### 1. Clone & Install Dependencies
```bash
git clone https://github.com/Lucdpt3105/MegaLearning.git
cd MegaLearning
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure:
```env
DB_DATABASE=learning3
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE learning3"

# Run migrations and seeders
php artisan migrate --seed
```

### 4. Build Assets
```bash
npm run build
# Or for development with hot reload
npm run dev
```

### 5. Start Server
```bash
php artisan serve
```

---

## ⚙️ Configuration

### Required Configuration

#### Database (Required)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=learning3
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Optional Configuration

#### Zoom Video Calls (Optional - 5 min setup)
1. Create Server-to-Server OAuth app at https://marketplace.zoom.us/develop/create
2. Add these scopes: `meeting:write:admin`, `meeting:read:admin`, `user:read:admin`
3. Configure in `.env`:
```env
ZOOM_ACCOUNT_ID=your_account_id
ZOOM_CLIENT_ID=your_client_id
ZOOM_CLIENT_SECRET=your_client_secret
```

**Quick Setup:**
```bash
scripts\setup-zoom.bat
```

**Verify:**
```bash
php scripts/check-zoom-config.php
php scripts/test-zoom-meeting.php
```

**Fallback:** Jitsi is available without any configuration!

#### Google Gemini AI (Optional - 2 min setup)
Get free API key from https://makersuite.google.com/app/apikey

```env
GEMINI_API_KEY=your_api_key
GEMINI_MODEL=gemini-2.0-flash-exp
```

**Quick Setup:**
```bash
scripts\setup-gemini.bat
```

**Test:**
```bash
php scripts/test-gemini.php
```

#### Email (Optional)
For development, emails are logged to `storage/logs/laravel.log`.

For production SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=your_email@gmail.com

```

---

## 📘 Usage Guide

### For Teachers

#### Create Video Call
1. Login as teacher
2. Navigate to **Video Calls** → **Create New**
3. Choose platform (Zoom or Jitsi)
4. Fill in details and submit
5. Share join link with students

#### Create Exam
1. Go to **Exams** → **Create Exam**
2. Add questions (multiple choice, true/false, essay)
3. Set duration and grading options
4. Publish to students

#### Grade Submissions
- Auto-grading for objective questions
- Manual grading for essay questions
- Bulk grading support

### For Students

#### Take Exam
1. Go to **My Exams**
2. Click **Start Exam**
3. Answer questions within time limit
4. Submit for grading

#### Join Video Call
1. View **Upcoming Video Calls**
2. Click **Join** button
3. Enter Zoom/Jitsi meeting

#### Use Chat & AI Assistant
1. Access **Chat** from sidebar
2. Ask questions to AI assistant
3. Get instant AI-powered answers

### For Admins

#### Manage Users
```bash
# List all users
php scripts/list-all-users.php

# Reset password (fastest way)
php scripts/reset-user-password.php user@email.com newpass

# Create test users
php scripts/create-test-chat-users.php
```

#### System Monitoring
```bash
# Check system status
scripts\system-check.bat

# Check Zoom config
php scripts/check-zoom-config.php
```

---

## 🔌 API Documentation

### Authentication

#### Get Dev Token (Development Only)
```http
POST /api/dev-token
Content-Type: application/json

{
  "email": "teacher@megalearning.com"
}
```

**Response:**
```json
{
  "success": true,
  "access_token": "1|xyz...",
  "token_type": "Bearer"
}
```

### API Endpoints

#### Categories API
```http
GET    /api/v1/categories           # List all
GET    /api/v1/categories/{id}      # Show one
POST   /api/v1/categories           # Create (auth)
PUT    /api/v1/categories/{id}      # Update (auth)
DELETE /api/v1/categories/{id}      # Delete (auth)
```

#### Subjects API
```http
GET    /api/v1/subjects             # List all
GET    /api/v1/subjects/{id}        # Show one
POST   /api/v1/subjects             # Create (auth)
PUT    /api/v1/subjects/{id}        # Update (auth)
DELETE /api/v1/subjects/{id}        # Delete (auth)
```

**Authentication:**
Add header: `Authorization: Bearer {token}`

**Full API Guide:** See [docs/api/THUNDER_CLIENT_API_GUIDE.md](docs/api/THUNDER_CLIENT_API_GUIDE.md)

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suites
```bash
# Feature tests
php artisan test --testsuite=Feature

# Unit tests
php artisan test --testsuite=Unit

# Browser tests (requires ChromeDriver)
php artisan dusk
```

### Test Coverage
- **26 Feature Tests** - Exam management, Chat system, Integration tests
- **10 Browser Tests** - UI automation with Laravel Dusk
- **36 Total Test Cases**

**Detailed Guide:** See [docs/testing/TESTING_GUIDE.md](docs/testing/TESTING_GUIDE.md)

---

## 🛠️ Scripts & Tools

### Password Management
```bash
# Reset any user password (fastest way)
php scripts/reset-user-password.php user@email.com newpassword

# Example
php scripts/reset-user-password.php student@megalearning.com 12345678
```

### Zoom Management
```bash
# Setup wizard
scripts\setup-zoom.bat

# Check configuration
php scripts/check-zoom-config.php

# Test API
php scripts/test-zoom-api.php

# Test meeting creation
php scripts/test-zoom-meeting.php
```

### AI Assistant
```bash
# Setup wizard
scripts\setup-gemini.bat

# Test Gemini AI
php scripts/test-gemini.php
```

### Database Management
```bash
# Fresh install with demo data
php artisan migrate:fresh --seed

# Check database status
php scripts/check-database.php
```

### System Checks
```bash
# Full system verification
scripts\system-check.bat

# List all scripts
dir scripts\*.bat
dir scripts\*.php
```

**All Scripts:** See [scripts/README.md](scripts/README.md)

---

## 🐛 Troubleshooting

### Database Issues

**Error: Access denied for user**
```bash
# Check credentials in .env
DB_USERNAME=root
DB_PASSWORD=your_password

# Clear config cache
php artisan config:clear
```

**Error: Database not found**
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE learning3"

# Then migrate
php artisan migrate
```

### Zoom API Issues

**Error: Invalid credentials**
```bash
# Verify credentials at https://marketplace.zoom.us
# Re-run setup wizard
scripts\setup-zoom.bat

# Or check config
php scripts/check-zoom-config.php
```

**Error: Insufficient permissions**
- Go to Zoom App → Scopes
- Add: meeting:write, meeting:read, user:read
- Click "Activate"

### Password Reset Issues

**Can't access email?**
```bash
# Use CLI tool instead
php scripts/reset-user-password.php user@email.com newpass123
```

**Forgot all passwords?**
```bash
# Reset all test accounts
php scripts/reset-user-password.php admin@megalearning.com password
php scripts/reset-user-password.php teacher@megalearning.com password
php scripts/reset-user-password.php student@megalearning.com password
```

### Common Fixes
```bash
# Clear all caches
php artisan optimize:clear

# Regenerate autoload
composer dump-autoload

# Rebuild assets
npm run build

# Restart server
php artisan serve
```

---

## 📁 Project Structure

```
MegaLearning/
├── app/
│   ├── Http/Controllers/      # Controllers
│   │   ├── Admin/             # Admin controllers
│   │   ├── Teacher/           # Teacher controllers
│   │   ├── Student/           # Student controllers
│   │   └── Api/               # API controllers
│   ├── Models/                # Eloquent models
│   ├── Services/              # Business logic
│   │   ├── ZoomService.php    # Zoom API integration
│   │   └── AIService.php      # Gemini AI integration
│   └── Policies/              # Authorization policies
│
├── database/
│   ├── migrations/            # Database migrations
│   ├── seeders/               # Database seeders
│   └── factories/             # Model factories
│
├── resources/
│   ├── views/                 # Blade templates
│   │   ├── admin/             # Admin views
│   │   ├── teacher/           # Teacher views
│   │   ├── student/           # Student views
│   │   ├── auth/              # Authentication views
│   │   └── chat/              # Chat views
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript
│
├── routes/
│   ├── web.php                # Web routes
│   ├── api.php                # API routes
│   └── channels.php           # Broadcasting channels
│
├── scripts/                   # Helper scripts
│   ├── setup-zoom.bat         # Zoom setup wizard
│   ├── setup-gemini.bat       # AI setup wizard
│   ├── reset-user-password.php
│   ├── test-zoom-api.php
│   └── test-gemini.php
│
├── tests/
│   ├── Feature/               # Feature tests
│   │   ├── ExamManagementTest.php
│   │   ├── ChatSystemTest.php
│   │   └── IntegrationTest.php
│   └── Browser/               # Browser tests
│       ├── ExamUITest.php
│       └── ChatUITest.php
│
└── docs/                      # Documentation
    ├── SETUP_VERIFICATION.md  # Setup status report
    └── DATABASE_SEEDING.md    # Seeding guide
```

**Full Structure:** See [docs/STRUCTURE.md](docs/STRUCTURE.md)

---

## 🎯 Quick Reference

### Most Used Commands
```bash
# Development
php artisan serve              # Start server
npm run dev                    # Start Vite (hot reload)
composer dev                   # Run both servers

# Database
php artisan migrate:fresh --seed    # Reset database
php artisan migrate                 # Run migrations
php artisan db:seed                 # Seed only

# Testing
php artisan test               # Run all tests
php artisan dusk               # Run browser tests

# Cache
php artisan optimize:clear     # Clear all caches
php artisan config:clear       # Clear config cache
php artisan view:clear         # Clear view cache

# Utilities
php artisan route:list         # List all routes
php artisan tinker             # Laravel REPL
```

### Key URLs
- Homepage: http://localhost:8000
- Admin Panel: http://localhost:8000/admin
- Teacher Dashboard: http://localhost:8000/teacher
- Student Dashboard: http://localhost:8000/student
- API Base: http://localhost:8000/api/v1
- Password Reset: http://localhost:8000/forgot-password

---

## 📚 Additional Documentation

### Setup & Configuration
- **[docs/setup/SETUP_VERIFICATION.md](docs/setup/SETUP_VERIFICATION.md)** - Detailed setup status report
- **[docs/setup/ZOOM_SETUP_GUIDE.md](docs/setup/ZOOM_SETUP_GUIDE.md)** - Complete Zoom integration guide
- **[docs/setup/SETUP_SUMMARY.md](docs/setup/SETUP_SUMMARY.md)** - All features overview

### User Guides
- **[docs/guides/PASSWORD_RESET_GUIDE.md](docs/guides/PASSWORD_RESET_GUIDE.md)** - Password reset methods
- **[docs/guides/ZOOM_QUICK_REF.md](docs/guides/ZOOM_QUICK_REF.md)** - Zoom quick reference

### Testing & API
- **[docs/testing/TESTING_GUIDE.md](docs/testing/TESTING_GUIDE.md)** - Testing documentation
- **[docs/api/THUNDER_CLIENT_API_GUIDE.md](docs/api/THUNDER_CLIENT_API_GUIDE.md)** - API testing guide

### Project Structure
- **[docs/STRUCTURE.md](docs/STRUCTURE.md)** - Detailed project structure
- **[docs/DATABASE_SEEDING.md](docs/DATABASE_SEEDING.md)** - Database seeding guide
- **[scripts/README.md](scripts/README.md)** - All helper scripts documentation

---

## 🎉 Status

✅ **FULLY OPERATIONAL & PRODUCTION READY**

- [x] Core learning features
- [x] Video conferencing (Zoom + Jitsi)
- [x] AI chat assistant (Gemini)
- [x] Real-time chat
- [x] Exam management
- [x] API with authentication
- [x] Testing suite (36 tests)
- [x] Documentation complete

**Ready for:**
- Development ✅
- Testing ✅
- Demo/Presentation ✅
- Production Deployment ✅

---

## 📞 Support

**Need Help?**

1. Check [Troubleshooting](#-troubleshooting) section
2. Review documentation in [docs/](docs/) folder
3. Run system check: `scripts\system-check.bat`
4. Check specific guides:
   - Password issues → [docs/guides/PASSWORD_RESET_GUIDE.md](docs/guides/PASSWORD_RESET_GUIDE.md)
   - Zoom issues → [docs/setup/ZOOM_SETUP_GUIDE.md](docs/setup/ZOOM_SETUP_GUIDE.md)
   - API issues → [docs/api/THUNDER_CLIENT_API_GUIDE.md](docs/api/THUNDER_CLIENT_API_GUIDE.md)

**Quick Diagnostics:**
```bash
php scripts/check-zoom-config.php    # Check Zoom
scripts\system-check.bat             # Full system check
```

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Luc Dang** - *Initial work* - [Lucdpt3105](https://github.com/Lucdpt3105)

---

<div align="center">

**Made with ❤️ using Laravel**

**Nhóm 5 - Nhập Môn Công Nghệ Phần Mềm - 2025-2026**

[⬆ Back to Top](#-megalearning---e-learning-platform)

</div># 3. Push lên GitHub
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
