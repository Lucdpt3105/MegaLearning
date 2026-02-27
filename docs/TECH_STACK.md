# 🛠️ Technology Stack - MegaLearning Platform

> Comprehensive documentation of all technologies, frameworks, libraries, and services used in the MegaLearning E-Learning Platform.

**Last Updated:** December 19, 2025  
**Project Version:** 1.0.0  
**Laravel Version:** 12.x  
**PHP Version:** 8.2+

---

## 📋 Table of Contents

1. [Backend Technologies](#-backend-technologies)
2. [Frontend Technologies](#-frontend-technologies)
3. [Database & Data Management](#-database--data-management)
4. [Real-time & Communication](#-real-time--communication)
5. [Third-Party Integrations](#-third-party-integrations)
6. [Authentication & Security](#-authentication--security)
7. [Testing & Quality Assurance](#-testing--quality-assurance)
8. [DevOps & Deployment](#-devops--deployment)
9. [Development Tools](#-development-tools)
10. [Build Tools & Asset Management](#-build-tools--asset-management)
11. [Code Quality & Standards](#-code-quality--standards)
12. [Monitoring & Logging](#-monitoring--logging)

---

## 🔧 Backend Technologies

### Core Framework
| Technology | Version | Purpose | Documentation |
|------------|---------|---------|---------------|
| **Laravel** | 12.x | PHP Web Application Framework | [laravel.com](https://laravel.com) |
| **PHP** | >= 8.2 | Server-side Programming Language | [php.net](https://php.net) |

### Laravel Ecosystem
| Package | Version | Purpose |
|---------|---------|---------|
| **Laravel Sanctum** | ^4.2 | API Token Authentication | [docs](https://laravel.com/docs/sanctum) |
| **Laravel Tinker** | ^2.10.1 | Interactive REPL Console | [docs](https://laravel.com/docs/artisan#tinker) |
| **Laravel Telescope** | ^5.15 | Debug & Monitoring Dashboard | [docs](https://laravel.com/docs/telescope) |
| **Laravel Pail** | ^1.2.2 | Real-time Log Viewer | [docs](https://laravel.com/docs/pail) |
| **Laravel Sail** | ^1.41 | Docker Development Environment | [docs](https://laravel.com/docs/sail) |

### Key PHP Packages

#### File & Document Management
| Package | Version | Purpose |
|---------|---------|---------|
| **Maatwebsite Excel** | 3.1.67 | Import/Export Excel/CSV files | [docs](https://docs.laravel-excel.com) |
| **PHPSpreadsheet** | Latest | Read/Write Spreadsheet files | [docs](https://phpspreadsheet.readthedocs.io) |
| **PHPOffice** | Latest | Office document manipulation | - |
| **Masbug Google Drive Ext** | ^2.4 | Google Drive Filesystem Integration | [GitHub](https://github.com/masbug/flysystem-google-drive-ext) |

#### Permissions & Settings
| Package | Version | Purpose |
|---------|---------|---------|
| **Spatie Laravel Permission** | ^6.23 | Role-Based Access Control (RBAC) | [docs](https://spatie.be/docs/laravel-permission) |
| **Akaunting Setting** | ^1.2 | Application Settings Management | [GitHub](https://github.com/akaunting/laravel-setting) |

#### Communication & Email
| Package | Version | Purpose |
|---------|---------|---------|
| **Symfony Brevo Mailer** | Latest | Email Service Integration (Brevo/Sendinblue) | [docs](https://symfony.com/doc/current/mailer.html) |
| **Pusher PHP Server** | ^7.2 | Real-time Broadcasting Server | [docs](https://github.com/pusher/pusher-http-php) |

### Architecture Patterns
- **MVC (Model-View-Controller)** - Core application structure
- **Service Layer Pattern** - Business logic isolation (AIService, ZoomService, etc.)
- **Repository Pattern** - Data access abstraction
- **Policy Pattern** - Authorization logic
- **Observer Pattern** - Event listeners
- **Singleton Pattern** - Service containers

---

## 🎨 Frontend Technologies

### Core Technologies
| Technology | Version | Purpose |
|------------|---------|---------|
| **Blade Templates** | Built-in | Laravel's templating engine |
| **HTML5** | Latest | Markup language |
| **CSS3** | Latest | Styling |
| **JavaScript (ES6+)** | Latest | Client-side interactivity |

### CSS Framework
| Framework | Version | Purpose | Documentation |
|-----------|---------|---------|---------------|
| **Tailwind CSS** | ^4.1.17 | Utility-first CSS Framework | [tailwindcss.com](https://tailwindcss.com) |
| **@tailwindcss/postcss** | ^4.1.17 | PostCSS Plugin for Tailwind | - |
| **@tailwindcss/vite** | ^4.0.0 | Vite Plugin for Tailwind | - |

### UI Components & Icons
- **Bootstrap Icons** - Icon library (via CDN)
- **Chart.js** - Data visualization (used in reports)
- **Custom Components** - Reusable Blade components

### JavaScript Libraries
| Library | Version | Purpose |
|---------|---------|---------|
| **Axios** | ^1.11.0 | HTTP client for API calls |
| **Laravel Echo** | Via CDN | WebSocket event broadcasting |
| **Pusher JS** | Via CDN | Real-time functionality |

---

## 🗄️ Database & Data Management

### Database System
| Technology | Version | Purpose |
|------------|---------|---------|
| **MySQL** | >= 8.0 | Primary relational database |
| **MariaDB** | >= 10.3 | Alternative RDBMS (compatible) |

### Database Tools
- **Eloquent ORM** - Laravel's database abstraction layer
- **Query Builder** - Fluent interface for queries
- **Migrations** - Version control for database schema (45 migration files)
- **Seeders** - Database population with test data
- **Factories** - Model factories for testing

### Database Structure
```
Total Tables: 45+
Key Tables:
├── users (Authentication)
├── roles, permissions, role_has_permissions (RBAC)
├── subjects, topics, questions, answers (Content)
├── exams, exam_submissions, grades (Assessment)
├── chat_rooms, chat_messages (Communication)
├── video_calls (Video Conferencing)
├── documents (File Management)
├── notifications (Notifications)
├── class_rooms, class_enrollments (Course Management)
├── student_rankings (Leaderboards)
└── attendance (Tracking)
```

### Data Integrity
- **Foreign Key Constraints** - Referential integrity
- **Unique Constraints** - Data uniqueness
- **Indexes** - Query optimization
- **Soft Deletes** - Data recovery capability
- **Timestamps** - Audit trails

---

## 🔄 Real-time & Communication

### Broadcasting
| Technology | Purpose |
|------------|---------|
| **Laravel Echo** | WebSocket client library |
| **Pusher** | Real-time broadcasting service (production) |
| **Log Driver** | Development broadcasting (no setup) |

### Real-time Features
- **Chat System** - Private messages, group chat, class chat
- **Notifications** - Real-time user notifications
- **Presence Channels** - Online user status
- **Event Broadcasting** - MessageSent, NotificationCreated events

### WebSocket Events
```php
Events\MessageSent
Events\NotificationCreated
Events\ExamStarted
Events\ExamEnded
```

---

## 🌐 Third-Party Integrations

### Video Conferencing
| Service | API Version | Purpose | Status |
|---------|-------------|---------|--------|
| **Zoom API** | v2 | Primary video conferencing | OAuth 2.0 |
| **Jitsi Meet** | Latest | Fallback video conferencing | No auth required |

**Zoom Features Implemented:**
- Server-to-Server OAuth authentication
- Meeting creation/management
- User management
- Recording management

**Required Scopes:**
- `meeting:write:admin`
- `meeting:read:admin`
- `user:read:admin`

### AI & Machine Learning
| Service | Model | Purpose | Status |
|---------|-------|---------|--------|
| **Google Gemini AI** | gemini-2.5-flash | AI Chat Assistant | API Key |

**AI Features:**
- Automatic Q&A responses
- Context-aware conversations
- Subject-specific assistance
- Mock responses (fallback mode)

### Email Services
| Service | Purpose | Configuration |
|---------|---------|---------------|
| **Brevo (Sendinblue)** | Transactional emails | SMTP/API |
| **SMTP** | Alternative email service | Configurable |

### File Storage
| Service | Purpose | Integration |
|---------|---------|-------------|
| **Google Drive** | Cloud file storage | Flysystem driver |
| **Local Storage** | Development/fallback | Laravel storage |
| **Amazon S3** | Production storage (optional) | AWS SDK |

---

## 🔐 Authentication & Security

### Authentication Systems
| System | Purpose |
|--------|---------|
| **Laravel Breeze** | Web authentication scaffolding |
| **Laravel Sanctum** | API token authentication |
| **Session-based Auth** | Web application authentication |

### Security Features
- **Password Hashing** - Bcrypt algorithm
- **CSRF Protection** - Cross-Site Request Forgery prevention
- **XSS Protection** - Cross-Site Scripting prevention
- **SQL Injection Prevention** - Eloquent ORM prepared statements
- **Rate Limiting** - API throttling
- **Role-Based Access Control (RBAC)** - Spatie Permission
- **Password Reset** - Email-based + CLI tool
- **Two-Factor Authentication** - Ready for implementation

### Exam Security
```php
Security Features:
├── Access codes
├── Time limits
├── IP restriction
├── Device detection
├── Tab switch detection
├── Screen recording requirement
├── Camera requirement
├── Question/answer randomization
└── Auto-submit on time expiry
```

---

## 🧪 Testing & Quality Assurance

### Testing Frameworks
| Framework | Version | Purpose |
|-----------|---------|---------|
| **PHPUnit** | ^11.5.3 | Unit & Feature testing |
| **Laravel Dusk** | ^8.3 | Browser automation testing |
| **Mockery** | ^1.6 | Mocking framework |

### Testing Tools
| Tool | Purpose |
|------|---------|
| **Thunder Client** | API testing (VS Code extension) |
| **Postman** | Alternative API testing |
| **Faker** | ^1.23 - Test data generation |

### Test Coverage
```
Test Suite Statistics:
├── Unit Tests: 15+ tests
├── Feature Tests: 20+ tests
├── Browser Tests: 5+ tests
├── API Tests: Full endpoint coverage
└── Integration Tests: Database + API
```

### Testing Commands
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage

# Browser tests
php artisan dusk

# Parallel testing
php artisan test --parallel
```

---

## 🚀 DevOps & Deployment

### Cloud Platform
| Service | Purpose |
|---------|---------|
| **AWS EC2** | Virtual server hosting |
| **AWS RDS** | Managed MySQL database |
| **AWS S3** | File/asset storage |
| **AWS CloudFront** | CDN (optional) |

### Web Servers
| Server | Purpose | Configuration |
|--------|---------|---------------|
| **Nginx** | Primary web server | Reverse proxy, SSL |
| **Apache** | Alternative web server | mod_php |

### Process Management
| Tool | Purpose |
|------|---------|
| **Supervisor** | Queue worker management |
| **PM2** | Node.js process manager (optional) |

### Containerization (Optional)
| Technology | Purpose |
|------------|---------|
| **Docker** | Application containerization |
| **Docker Compose** | Multi-container orchestration |
| **Laravel Sail** | Docker development environment |

### CI/CD Pipeline
```yaml
Deployment Workflow:
1. Git push to main/production branch
2. Run automated tests (PHPUnit + Dusk)
3. Build frontend assets (Vite)
4. Deploy to AWS EC2
5. Run migrations (zero-downtime)
6. Clear caches
7. Restart queue workers
8. Health check
```

### Deployment Commands
```bash
# Production deployment
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🛠️ Development Tools

### IDEs & Editors
| Tool | Purpose | Extensions |
|------|---------|-----------|
| **Visual Studio Code** | Primary code editor | Laravel, PHP Intelephense, Tailwind CSS |
| **PhpStorm** | Advanced PHP IDE | Laravel plugin, database tools |
| **Sublime Text** | Lightweight editor | - |

### Version Control
| Tool | Version | Purpose |
|------|---------|---------|
| **Git** | Latest | Version control system |
| **GitHub** | - | Code hosting & collaboration |

### Package Managers
| Manager | Purpose |
|---------|---------|
| **Composer** | PHP dependency management |
| **npm** | JavaScript dependency management |

### Database Management
| Tool | Purpose |
|------|---------|
| **MySQL Workbench** | Database design & management |
| **phpMyAdmin** | Web-based database admin |
| **TablePlus** | Modern database client |
| **DBeaver** | Universal database tool |

### API Development
| Tool | Purpose |
|------|---------|
| **Thunder Client** | VS Code API testing |
| **Postman** | API development & testing |
| **Insomnia** | Alternative API client |

### Browser DevTools
- **Chrome DevTools** - Primary debugging
- **Firefox Developer Tools** - Cross-browser testing
- **Laravel Telescope** - Application debugging

---

## ⚡ Build Tools & Asset Management

### Build System
| Tool | Version | Purpose |
|------|---------|---------|
| **Vite** | ^7.0.7 | Modern frontend build tool |
| **Laravel Vite Plugin** | ^2.0.0 | Laravel integration for Vite |

### CSS Processing
| Tool | Version | Purpose |
|------|---------|---------|
| **PostCSS** | ^8.5.6 | CSS transformation |
| **Autoprefixer** | ^10.4.21 | CSS vendor prefixing |

### Asset Optimization
- **Vite Code Splitting** - Optimized bundle sizes
- **Tree Shaking** - Remove unused code
- **CSS Purging** - Remove unused Tailwind classes
- **Minification** - Compress JS/CSS for production
- **Image Optimization** - Lazy loading, compression

### Build Commands
```bash
# Development (hot reload)
npm run dev

# Production build
npm run build

# Watch mode
npm run watch
```

---

## 📏 Code Quality & Standards

### Code Style & Linting
| Tool | Version | Purpose |
|------|---------|---------|
| **Laravel Pint** | ^1.24 | PHP code style fixer |
| **PHPStan** | Optional | Static analysis |
| **ESLint** | Optional | JavaScript linting |

### Code Standards
- **PSR-12** - PHP coding standard
- **Laravel Best Practices** - Framework conventions
- **SOLID Principles** - Object-oriented design

### Code Quality Tools
```bash
# Fix code style
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test

# Run static analysis
./vendor/bin/phpstan analyse
```

---

## 📊 Monitoring & Logging

### Application Monitoring
| Tool | Purpose |
|------|---------|
| **Laravel Telescope** | Development debugging & monitoring |
| **Laravel Pail** | Real-time log viewer |
| **AWS CloudWatch** | Production monitoring (optional) |

### Logging
| Channel | Purpose |
|---------|---------|
| **Stack** | Multiple log channels |
| **Daily** | Daily rotating log files |
| **Slack** | Critical error notifications (optional) |
| **Stderr/Stdout** | Console logging |

### Error Tracking
- **Laravel Exception Handler** - Built-in error handling
- **Sentry** - Error tracking (optional, for production)
- **Bugsnag** - Alternative error tracking (optional)

### Performance Monitoring
```php
Metrics Tracked:
├── Response times
├── Database query counts
├── Cache hit rates
├── Queue job processing
├── Memory usage
└── API request rates
```

---

## 📦 Additional Dependencies

### Development Dependencies
```json
{
  "fakerphp/faker": "^1.23",
  "laravel/dusk": "^8.3",
  "laravel/pail": "^1.2.2",
  "laravel/pint": "^1.24",
  "laravel/sail": "^1.41",
  "mockery/mockery": "^1.6",
  "nunomaduro/collision": "^8.6",
  "phpunit/phpunit": "^11.5.3"
}
```

### Frontend Dependencies
```json
{
  "@tailwindcss/postcss": "^4.1.17",
  "@tailwindcss/vite": "^4.0.0",
  "autoprefixer": "^10.4.21",
  "axios": "^1.11.0",
  "concurrently": "^9.0.1",
  "laravel-vite-plugin": "^2.0.0",
  "postcss": "^8.5.6",
  "tailwindcss": "^4.1.17",
  "vite": "^7.0.7"
}
```

---

## 🔗 External Services & APIs

### Currently Integrated
| Service | Type | Status | Documentation |
|---------|------|--------|---------------|
| **Zoom API** | Video Conferencing | Active | [docs.zoom.us](https://docs.zoom.us) |
| **Google Gemini AI** | AI Assistant | Active | [ai.google.dev](https://ai.google.dev) |
| **Pusher** | Real-time Broadcasting | Active | [pusher.com](https://pusher.com) |
| **Brevo** | Email Service | Active | [brevo.com](https://brevo.com) |
| **Google Drive** | File Storage | Active | [drive.google.com](https://drive.google.com) |
| **Jitsi Meet** | Video Conferencing | Fallback | [jitsi.org](https://jitsi.org) |

### Ready for Integration
- **Stripe/PayPal** - Payment processing
- **Twilio** - SMS notifications
- **AWS Polly** - Text-to-speech
- **AWS Rekognition** - Facial recognition (exam proctoring)

---

## 📚 Helper Scripts & Utilities

### Custom Scripts (20+ utilities)
```
scripts/
├── setup-gemini.bat          # Gemini AI setup
├── test-gemini.php           # Test AI integration
├── setup-zoom.bat            # Zoom API setup
├── test-zoom-api.php         # Test Zoom integration
├── test-zoom-meeting.php     # Create test meeting
├── chat-start.bat            # Start chat system
├── check-chat-status.php     # Verify chat functionality
├── create-test-chat-users.php # Generate test users
├── test-chat-api.ps1         # API testing script
├── test-auto-grading.php     # Test grading logic
├── create-grading-test-data.php # Generate test submissions
├── fix-missing-answers.php   # Data cleanup utility
├── reset-badge-test.bat      # Reset badge system
├── test-badge-feature.bat    # Test achievement system
├── list-all-users.php        # User management
└── ... (see scripts/ directory)
```

---

## 🎯 System Architecture Summary

### Architectural Layers
```
┌─────────────────────────────────────────┐
│         Presentation Layer              │
│   (Blade, Tailwind, JavaScript)         │
├─────────────────────────────────────────┤
│         Application Layer               │
│   (Controllers, Middleware, Policies)   │
├─────────────────────────────────────────┤
│         Business Logic Layer            │
│   (Services, Events, Observers)         │
├─────────────────────────────────────────┤
│         Data Access Layer               │
│   (Eloquent Models, Repositories)       │
├─────────────────────────────────────────┤
│         Database Layer                  │
│   (MySQL, Migrations, Seeders)          │
└─────────────────────────────────────────┘
```

### Design Patterns Used
- **MVC (Model-View-Controller)**
- **Service Layer**
- **Repository Pattern**
- **Factory Pattern**
- **Observer Pattern**
- **Policy Pattern**
- **Strategy Pattern**
- **Singleton Pattern**

---

## 📖 Documentation Resources

### Official Documentation
- **Laravel:** https://laravel.com/docs/12.x
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Vite:** https://vite.dev
- **PHPUnit:** https://phpunit.de/documentation.html
- **Spatie Permission:** https://spatie.be/docs/laravel-permission

### API Documentation
- **Zoom API:** https://developers.zoom.us
- **Google Gemini AI:** https://ai.google.dev/docs
- **Pusher:** https://pusher.com/docs
- **Laravel Sanctum:** https://laravel.com/docs/12.x/sanctum

### Learning Resources
- **Laracasts:** https://laracasts.com
- **Laravel News:** https://laravel-news.com
- **PHP The Right Way:** https://phptherightway.com

---

## 🔄 Version History

| Version | Date | Major Changes |
|---------|------|---------------|
| 1.0.0 | Dec 2025 | Initial production release |
| 0.9.0 | Nov 2025 | Beta testing phase |
| 0.5.0 | Oct 2025 | Core features complete |
| 0.1.0 | Sep 2025 | Project initialization |

---

## 📞 Support & Resources

### Project Resources
- **GitHub Repository:** https://github.com/Lucdpt3105/MegaLearning
- **Issue Tracker:** GitHub Issues
- **Wiki:** GitHub Wiki
- **Project Board:** Jira/GitHub Projects

### Community
- **Stack Overflow:** Tag with `megalearning`
- **Laravel Community:** https://laravel.io
- **Discord:** (if applicable)

---

## 📝 License & Credits

### License
This project is licensed under the **MIT License**.

### Credits & Acknowledgments
- **Laravel Framework** - Taylor Otwell
- **Tailwind CSS** - Adam Wathan
- **Spatie** - Permission package
- **All contributors** - See CONTRIBUTORS.md

---

**Document Version:** 1.0  
**Last Updated:** December 19, 2025  
**Maintained By:** MegaLearning Development Team

For more information, see [README.md](README.md) and [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md).
