# 📊 Cấu Trúc Dự Án MegaLearning

> **Tài liệu chi tiết về cấu trúc thư mục và tổ chức code của dự án**

---

## 📋 Mục lục

- [Tổng quan](#-tổng-quan)
- [Cấu trúc thư mục tổng thể](#-cấu-trúc-thư-mục-tổng-thể)
- [Chi tiết các thư mục](#-chi-tiết-các-thư-mục)
- [File quan trọng](#-file-quan-trọng)
- [Naming Convention](#-naming-convention)
- [Best Practices](#-best-practices)

---

## 🎯 Tổng quan

MegaLearning được xây dựng theo kiến trúc **MVC (Model-View-Controller)** của Laravel 12 với các tính năng:

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS 4
- **Database**: MySQL 8.0
- **Realtime**: Pusher + Laravel Echo
- **AI Integration**: Google Gemini API
- **Video**: Zoom API + Jitsi

---

## 📂 Cấu trúc thư mục tổng thể

### Root Level

```
MegaLearning/
├── app/              # Application core logic
├── bootstrap/        # Framework bootstrap files
├── config/           # Configuration files
├── database/         # Database migrations, seeders, factories
├── docs/             ⭐ Project documentation (ORGANIZED!)
├── public/           # Public web root (assets, index.php)
├── resources/        # Views, CSS, JS
├── routes/           # Route definitions
├── scripts/          ⭐ Utility scripts and tools
├── storage/          # Logs, cache, uploads
├── tests/            # Automated tests
└── vendor/           # Composer dependencies
```

### Documentation Structure (docs/)

```
docs/
├── README.md                    ⭐ Documentation hub/index
├── STRUCTURE.md                 📊 This file
├── DATABASE_SEEDING.md          🌱 Seeding guide
│
├── setup/                       ⚙️ Setup & Configuration
│   ├── SETUP_VERIFICATION.md   ✅ Setup status
│   ├── SETUP_SUMMARY.md        📋 Features overview
│   └── ZOOM_SETUP_GUIDE.md     📹 Zoom guide
│
├── guides/                      👥 User Guides
│   ├── PASSWORD_RESET_GUIDE.md 🔐 Password reset
│   └── ZOOM_QUICK_REF.md       📹 Quick reference
│
├── testing/                     🧪 Testing
│   └── TESTING_GUIDE.md        ✅ Test guide
│
├── api/                         🔌 API Docs
│   └── THUNDER_CLIENT_API_GUIDE.md
│
├── requirements/                📝 Requirements
│   └── Nhom5-Đe5.pdf
│
└── uml/                         📐 Diagrams
    ├── admin+hethong.jpg
    ├── giaovien.jpg
    └── học sinh.jpg

## 📁 Chi tiết các thư mục

### 1️⃣ **`app/`** - Application Core

Chứa logic nghiệp vụ chính của ứng dụng.

```
app/
├── Console/
│   └── Commands/              # Custom Artisan commands
├── Events/
│   └── MessageSent.php       # ⭐ Chat broadcast event
├── Http/
│   ├── Controllers/
│   │   ├── Api/              # RESTful API Controllers
│   │   │   ├── AuthApiController.php
│   │   │   ├── ChatApiController.php      # ⭐ Chat API
│   │   │   ├── ExamController.php
│   │   │   ├── QuestionController.php
│   │   │   ├── SubjectController.php
│   │   │   └── TopicController.php
│   │   ├── AuthController.php
│   │   ├── ChatController.php             # ⭐ Web chat
│   │   ├── Controller.php                 # Base controller
│   │   ├── StudentController.php
│   │   └── TeacherController.php
│   ├── Middleware/            # HTTP middleware
│   └── Requests/              # Form request validation
├── Models/                    # Eloquent ORM Models
│   ├── Answer.php
│   ├── ChatMessage.php        # ⭐
│   ├── ChatRoom.php           # ⭐
│   ├── Exam.php
│   ├── Question.php
│   ├── Subject.php
│   ├── Topic.php
│   └── User.php
├── Providers/
│   └── AppServiceProvider.php # Service container bindings
└── Services/
    └── AIService.php          # ⭐ Gemini AI integration
```

#### **Controllers Organization:**

- **`Api/`**: RESTful API endpoints (JSON responses)
- **Root level**: Web controllers (Blade views)

#### **Models:**

- Eloquent ORM models
- Relationships, accessors, mutators
- Query scopes

#### **Services:**

- Business logic layer
- External API integrations (Gemini AI)
- Reusable service classes

---

### 2️⃣ **`database/`** - Database Layer

```
database/
├── learning3.sql              # Database backup
├── factories/
│   └── UserFactory.php       # Model factories for testing
├── migrations/               # Database schema migrations
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2025_11_07_104721_create_subjects_table.php
│   ├── 2025_11_07_104725_create_topics_table.php
│   ├── 2025_11_07_104730_create_questions_table.php
│   ├── 2025_11_07_104734_create_answers_table.php
│   ├── 2025_11_07_104736_create_exams_table.php
│   ├── 2025_11_11_000001_create_chat_rooms_table.php        # ⭐
│   ├── 2025_11_11_000002_create_chat_messages_table.php     # ⭐
│   ├── 2025_11_11_000003_create_chat_room_members_table.php # ⭐
│   └── 2025_11_11_100000_add_ai_role.php                    # ⭐
└── seeders/
    ├── ChatDemoSeeder.php    # ⭐ Chat demo data
    └── DatabaseSeeder.php    # Main seeder
```

#### **Migrations naming convention:**

```
{timestamp}_{action}_{table_name}_table.php

Examples:
- create_users_table.php
- add_status_to_exams_table.php
- alter_questions_table.php
```

---

### 3️⃣ **`resources/`** - Frontend Assets

```
resources/
├── css/
│   └── app.css               # Tailwind CSS entry point
├── js/
│   ├── app.js               # Main JS entry
│   └── bootstrap.js         # Laravel Echo, Pusher setup
└── views/                   # Blade templates
    ├── admin/               # Admin dashboard
    │   ├── dashboard.blade.php
    │   ├── subjects/
    │   ├── topics/
    │   ├── questions/
    │   └── exams/
    ├── auth/                # Authentication views
    │   ├── login.blade.php
    │   └── register.blade.php
    ├── chat/                # ⭐ Chat interface
    │   └── index.blade.php
    ├── components/          # Reusable Blade components
    │   ├── stat-card.blade.php
    │   ├── recent-quizzes.blade.php
    │   └── ...
    ├── dashboard/           # Main dashboard
    │   └── index.blade.php
    ├── layouts/             # Layout templates
    │   ├── app.blade.php   # Main layout
    │   └── partials/
    │       ├── header.blade.php
    │       └── sidebar.blade.php
    ├── teacher/            # Teacher dashboard
    └── welcome.blade.php   # Landing page
```

#### **Views Organization:**

- **Layouts**: Master templates với `@yield()` sections
- **Partials**: Reusable components (header, sidebar, etc.)
- **Components**: Blade components với `@include()`
- **Feature folders**: Grouped by feature (admin, teacher, chat)

---

### 4️⃣ **`routes/`** - Route Definitions

```
routes/
├── api.php        # RESTful API routes (/api/*)
├── channels.php   # Broadcasting authorization channels
├── console.php    # Artisan command definitions
└── web.php        # Web routes (Blade views)
```

#### **Route Organization:**

**`web.php`** - Web routes:
```php
Route::get('/chat', ...);           // Public routes
Route::middleware(['auth'])->...;   // Authenticated routes
Route::prefix('admin')->...;        // Admin routes
```

**`api.php`** - API routes (prefix: `/api`):
```php
Route::prefix('chat')->group(...);  // Chat API
Route::prefix('v1')->group(...);    // Versioned API
```

---

### 5️⃣ **`scripts/`** - Utility Scripts ⭐

```
scripts/
├── README.md                  # Scripts documentation
├── setup-gemini.bat          # Setup Gemini AI API
├── chat-start.bat            # Quick start chat server
├── check-security.bat        # Security checker
├── create-users.bat          # Create demo users
├── test-gemini.php           # Test Gemini API
├── test-chat-direct.php      # Test chat API directly
├── test-chat-api.ps1         # PowerShell API tester
├── test-chat-no-auth.bat     # Test chat without auth
├── test-openai.bat           # Test OpenAI (legacy)
└── list-gemini-models.php    # List available Gemini models
```

#### **Purpose:**

- Development utilities
- Testing scripts
- Setup automation
- Quick start tools

---

### 6️⃣ **`docs/`** - Documentation

```
docs/
├── README.md              # Documentation index
├── api/                   # API documentation
├── requirements/          # Requirements specifications
│   └── Nhom5-Đe5.pdf
├── setup/                 # Setup guides
└── uml/                   # UML diagrams
    ├── admin+hethong.jpg
    ├── giaovien.jpg
    ├── học sinh.jpg
    └── Đặc Tả Yêu Cầu Chức Năng.txt
```

---

### 7️⃣ **`config/`** - Configuration

```
config/
├── app.php              # Application config
├── auth.php             # Authentication config
├── broadcasting.php     # ⭐ Pusher config
├── cache.php
├── database.php
├── filesystems.php
├── logging.php
├── mail.php
├── permission.php       # Spatie permissions
├── queue.php
├── sanctum.php          # API authentication
├── services.php         # ⭐ Gemini API config
└── session.php
```

---

## 📝 Naming Convention

### Files and Folders

| Type | Convention | Example |
|------|-----------|---------|
| **Controllers** | PascalCase + Controller suffix | `ChatApiController.php` |
| **Models** | PascalCase (singular) | `ChatMessage.php` |
| **Views** | kebab-case | `index.blade.php` |
| **Migrations** | snake_case | `create_chat_rooms_table.php` |
| **Routes** | kebab-case | `/chat-demo` |
| **CSS/JS** | kebab-case | `app.css` |

### Code Convention

```php
// Classes: PascalCase
class ChatApiController extends Controller {}

// Methods: camelCase
public function sendMessage() {}

// Variables: camelCase
$chatRoom = ChatRoom::find($id);

// Constants: UPPER_SNAKE_CASE
const MAX_MESSAGE_LENGTH = 5000;

// Database tables: snake_case (plural)
Schema::create('chat_messages', ...);

// Model properties: snake_case
protected $fillable = ['message_text', 'room_id'];
```

---

## ✅ Best Practices

### 1. **Separation of Concerns**

- **Controllers**: Handle HTTP requests, delegate to services
- **Models**: Database interaction, relationships
- **Services**: Business logic, external APIs
- **Views**: Presentation only, minimal logic

### 2. **DRY (Don't Repeat Yourself)**

- Use Blade components for reusable UI
- Create service classes for shared logic
- Use traits for reusable model behavior

### 3. **Security**

- CSRF protection on all forms
- Validate all inputs
- Use middleware for authentication
- Sanitize user inputs

### 4. **Performance**

- Eager load relationships (avoid N+1)
- Cache frequently accessed data
- Optimize database queries
- Use queues for heavy tasks

### 5. **Code Organization**

- Group related files together
- Keep controllers thin
- Use form requests for validation
- Document complex logic

---

## 🔍 Quick Reference

### Find a feature:

| Feature | Location |
|---------|----------|
| **Chat UI** | `resources/views/chat/index.blade.php` |
| **Chat API** | `app/Http/Controllers/Api/ChatApiController.php` |
| **Gemini AI** | `app/Services/AIService.php` |
| **User Auth** | `app/Http/Controllers/AuthController.php` |
| **Database** | `database/migrations/` |
| **Routes** | `routes/web.php`, `routes/api.php` |
| **Config** | `config/services.php` (Gemini), `config/broadcasting.php` (Pusher) |

---

## 📚 Related Documentation

- [README.md](README.md) - Main project documentation
- [docs/README.md](docs/README.md) - Detailed docs index
- [scripts/README.md](scripts/README.md) - Scripts documentation
- [INSTALLATION.md](INSTALLATION.md) - Installation guide
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - API reference

---

**Last Updated:** November 12, 2025  
**Maintainer:** Nhóm 5 - CNPM 2025
