@echo off
REM ============================================
REM MegaLearning - Database Seeding Script
REM ============================================
REM This script will populate the database with
REM comprehensive demo data for testing
REM ============================================

echo.
echo ========================================
echo  MegaLearning - Database Seeding
echo ========================================
echo.

REM Check if .env file exists
if not exist ".env" (
    echo [ERROR] .env file not found!
    echo Please copy .env.example to .env first
    pause
    exit /b 1
)

echo [1/4] Checking database connection...
php artisan db:show 2>nul
if errorlevel 1 (
    echo [WARNING] Cannot connect to database. Please check .env configuration.
    echo.
    set /p continue="Continue anyway? (y/n): "
    if /i not "%continue%"=="y" exit /b 1
)

echo.
echo [2/4] WARNING: This will DELETE all existing data!
echo.
set /p confirm="Are you sure you want to proceed? (yes/no): "
if /i not "%confirm%"=="yes" (
    echo Operation cancelled.
    pause
    exit /b 0
)

echo.
echo [3/4] Running migrations (fresh)...
php artisan migrate:fresh --force
if errorlevel 1 (
    echo [ERROR] Migration failed!
    pause
    exit /b 1
)

echo.
echo [4/4] Seeding database with demo data...
php artisan db:seed --class=DatabaseSeeder
if errorlevel 1 (
    echo [ERROR] Seeding failed!
    pause
    exit /b 1
)

echo.
echo ========================================
echo  SEEDING COMPLETED SUCCESSFULLY!
echo ========================================
echo.
echo Demo Accounts:
echo   Admin:   admin@megalearning.com    / password
echo   Teacher: teacher@megalearning.com  / password
echo   Student: student@megalearning.com  / password
echo.
echo Database now contains:
echo   - Roles and Permissions
echo   - 3+ Users (Admin, Teacher, Students)
echo   - 5+ Subjects with Topics
echo   - 60+ Questions with Answers
echo   - 20+ Sample Documents
echo   - Classes with Enrollments
echo   - Chat Rooms and Messages
echo   - Forum Discussions
echo   - Exams
echo.
echo Ready to test at: http://127.0.0.1:8000
echo.
pause
