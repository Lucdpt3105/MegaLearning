# 📚 MegaLearning Documentation

Welcome to the MegaLearning documentation hub. All project documentation is organized here for easy navigation.

---

## 📖 Table of Contents

- [Quick Start](#-quick-start)
- [Setup & Configuration](#-setup--configuration)
- [User Guides](#-user-guides)
- [Testing & Quality](#-testing--quality)
- [API Documentation](#-api-documentation)
- [Project Architecture](#-project-architecture)

---

## 🚀 Quick Start

**New to the project?** Start here:

1. **[../README.md](../README.md)** - Main project README with quick start guide
2. **[setup/SETUP_VERIFICATION.md](setup/SETUP_VERIFICATION.md)** - Verify your setup is complete
3. **[setup/SETUP_SUMMARY.md](setup/SETUP_SUMMARY.md)** - Overview of all features

---

## ⚙️ Setup & Configuration

### Initial Setup
- **[setup/SETUP_SUMMARY.md](setup/SETUP_SUMMARY.md)** - Complete feature overview and status
- **[setup/SETUP_VERIFICATION.md](setup/SETUP_VERIFICATION.md)** - Setup verification report with test results

### Optional Services
- **[setup/ZOOM_SETUP_GUIDE.md](setup/ZOOM_SETUP_GUIDE.md)** - Zoom video conferencing integration (5-minute setup)
  - Step-by-step OAuth app creation
  - Environment configuration
  - Testing and verification
  - Troubleshooting tips

### Database
- **[DATABASE_SEEDING.md](DATABASE_SEEDING.md)** - Database seeding guide
  - Migration workflow
  - Seeder creation
  - Demo data setup

---

## 👥 User Guides

### Authentication & Security
- **[guides/PASSWORD_RESET_GUIDE.md](guides/PASSWORD_RESET_GUIDE.md)** - Password reset methods
  - CLI tool (fastest)
  - Web interface
  - Tinker method
  - Common issues

### Video Conferencing
- **[guides/ZOOM_QUICK_REF.md](guides/ZOOM_QUICK_REF.md)** - Zoom quick reference card
  - Common commands
  - Configuration variables
  - API endpoints
  - Troubleshooting checklist

---

## 🧪 Testing & Quality

### Test Suites
- **[testing/TESTING_GUIDE.md](testing/TESTING_GUIDE.md)** - Complete testing documentation
  - 36 test cases (26 Feature + 10 Browser)
  - PHPUnit configuration
  - Laravel Dusk browser tests
  - Running test suites
  - Test coverage reports

### Test Data
- **[DATABASE_SEEDING.md](DATABASE_SEEDING.md)** - Test data generation
- **[../scripts/README.md](../scripts/README.md)** - Helper scripts for testing

---

## 🔌 API Documentation

### REST API
- **[api/THUNDER_CLIENT_API_GUIDE.md](api/THUNDER_CLIENT_API_GUIDE.md)** - API testing guide
  - Authentication (Sanctum)
  - Get dev tokens
  - API endpoints (Categories, Subjects, Topics, Questions, Exams)
  - Thunder Client collection
  - Example requests/responses

### API Reference
All API endpoints use base URL: `http://localhost:8000/api/v1`

**Quick Links:**
- Authentication: See [api/THUNDER_CLIENT_API_GUIDE.md](api/THUNDER_CLIENT_API_GUIDE.md#authentication)
- Categories API: See [api/THUNDER_CLIENT_API_GUIDE.md](api/THUNDER_CLIENT_API_GUIDE.md#categories-api)
- Subjects API: See [api/THUNDER_CLIENT_API_GUIDE.md](api/THUNDER_CLIENT_API_GUIDE.md#subjects-api)

---

## 🏗️ Project Architecture

### Structure & Organization
- **[STRUCTURE.md](STRUCTURE.md)** - Detailed project structure
  - Directory layout
  - File organization
  - Module descriptions
  - Important files reference

### UML Diagrams
Located in `uml/` folder:
- **[uml/admin+hethong.jpg](uml/admin+hethong.jpg)** - Admin & System diagram
- **[uml/giaovien.jpg](uml/giaovien.jpg)** - Teacher module diagram
- **[uml/học sinh.jpg](uml/học sinh.jpg)** - Student module diagram

### Requirements
- **[requirements/Nhom5-Đe5.pdf](requirements/Nhom5-Đe5.pdf)** - Project requirements document

---

## 🛠️ Helper Scripts

All utility scripts are documented in:
- **[../scripts/README.md](../scripts/README.md)** - Complete scripts reference

**Categories:**
- Password management
- Zoom API testing
- Gemini AI testing
- Database utilities
- System checks

---

## 📂 Documentation Structure

```
docs/
├── README.md                    # This file (documentation hub)
├── STRUCTURE.md                 # Project structure
├── DATABASE_SEEDING.md          # Database seeding guide
│
├── setup/                       # Setup & configuration
│   ├── SETUP_VERIFICATION.md   # Setup verification report
│   ├── SETUP_SUMMARY.md        # Features overview
│   └── ZOOM_SETUP_GUIDE.md     # Zoom integration guide
│
├── guides/                      # User guides
│   ├── PASSWORD_RESET_GUIDE.md # Password reset methods
│   └── ZOOM_QUICK_REF.md       # Zoom quick reference
│
├── testing/                     # Testing documentation
│   └── TESTING_GUIDE.md        # Complete testing guide
│
├── api/                         # API documentation
│   └── THUNDER_CLIENT_API_GUIDE.md  # API testing guide
│
├── requirements/                # Requirements & specs
│   └── Nhom5-Đe5.pdf           # Project requirements
│
└── uml/                         # UML diagrams
    ├── admin+hethong.jpg
    ├── giaovien.jpg
    └── học sinh.jpg
```

---

## 🔍 Quick Search

### By Topic

**Setup & Installation:**
- New project setup → [setup/SETUP_SUMMARY.md](setup/SETUP_SUMMARY.md)
- Verify installation → [setup/SETUP_VERIFICATION.md](setup/SETUP_VERIFICATION.md)
- Database setup → [DATABASE_SEEDING.md](DATABASE_SEEDING.md)

**Features:**
- Zoom video calls → [setup/ZOOM_SETUP_GUIDE.md](setup/ZOOM_SETUP_GUIDE.md)
- Password reset → [guides/PASSWORD_RESET_GUIDE.md](guides/PASSWORD_RESET_GUIDE.md)
- API usage → [api/THUNDER_CLIENT_API_GUIDE.md](api/THUNDER_CLIENT_API_GUIDE.md)

**Development:**
- Project structure → [STRUCTURE.md](STRUCTURE.md)
- Testing → [testing/TESTING_GUIDE.md](testing/TESTING_GUIDE.md)
- Scripts → [../scripts/README.md](../scripts/README.md)

**Troubleshooting:**
- Zoom issues → [setup/ZOOM_SETUP_GUIDE.md](setup/ZOOM_SETUP_GUIDE.md#troubleshooting)
- Password issues → [guides/PASSWORD_RESET_GUIDE.md](guides/PASSWORD_RESET_GUIDE.md#troubleshooting)
- API issues → [api/THUNDER_CLIENT_API_GUIDE.md](api/THUNDER_CLIENT_API_GUIDE.md#troubleshooting)

---

## 📞 Need Help?

1. Check the relevant guide above
2. Run system diagnostics:
   ```bash
   scripts\system-check.bat
   php scripts/check-zoom-config.php
   ```
3. See main [README.md](../README.md) troubleshooting section

---

## 🤝 Contributing to Documentation

When adding new documentation:

1. **Choose the right folder:**
   - Setup guides → `setup/`
   - User guides → `guides/`
   - Testing docs → `testing/`
   - API docs → `api/`

2. **Update this index** (docs/README.md)

3. **Link from main README** if important

4. **Use consistent formatting:**
   - Clear headings
   - Code examples
   - Tables for reference data
   - Links to related docs

---

<div align="center">

**MegaLearning Documentation**

[⬆ Back to Top](#-megalearning-documentation) | [Main README](../README.md)

</div>
