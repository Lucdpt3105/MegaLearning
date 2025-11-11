@echo off
echo ========================================
echo MegaLearning Database Setup
echo ========================================
echo.

echo This script will help you setup the database.
echo.
echo Prerequisites:
echo - MySQL Server is running
echo - You have MySQL credentials
echo.

set /p DB_HOST="Enter MySQL Host (default: localhost): "
if "%DB_HOST%"=="" set DB_HOST=localhost

set /p DB_PORT="Enter MySQL Port (default: 3306): "
if "%DB_PORT%"=="" set DB_PORT=3306

set /p DB_USER="Enter MySQL User (default: root): "
if "%DB_USER%"=="" set DB_USER=root

set /p DB_PASSWORD="Enter MySQL Password: "

set DB_NAME=learning3

echo.
echo Creating database '%DB_NAME%'...
echo.

mysql -h %DB_HOST% -P %DB_PORT% -u %DB_USER% -p%DB_PASSWORD% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ✓ Database created successfully!
    echo.
    
    echo Updating .env file...
    (
        echo # Server
        echo PORT=8080
        echo NODE_ENV=development
        echo.
        echo # Database
        echo DB_HOST=%DB_HOST%
        echo DB_PORT=%DB_PORT%
        echo DB_NAME=%DB_NAME%
        echo DB_USER=%DB_USER%
        echo DB_PASSWORD=%DB_PASSWORD%
        echo.
        echo # JWT
        echo JWT_SECRET=megalearning-jwt-secret-key-2024-change-this-in-production
        echo JWT_EXPIRES_IN=24h
        echo.
        echo # OpenAI
        echo OPENAI_API_KEY=your-openai-api-key-here
        echo OPENAI_MODEL=gpt-3.5-turbo
        echo OPENAI_MAX_TOKENS=500
        echo.
        echo # CORS
        echo CORS_ORIGIN=http://localhost:5173,http://localhost:3000
    ) > .env
    
    echo ✓ .env file updated!
    echo.
    
    echo Running database seeder...
    call npm run seed
    
    if %ERRORLEVEL% EQU 0 (
        echo.
        echo ========================================
        echo Database setup completed successfully!
        echo ========================================
        echo.
        echo Database: %DB_NAME%
        echo Host: %DB_HOST%:%DB_PORT%
        echo.
        echo Test accounts created:
        echo - Admin: admin@megalearning.com / admin123
        echo - Teacher: teacher@megalearning.com / teacher123
        echo - Student: student@megalearning.com / student123
        echo.
        echo You can now run: npm run dev
        echo ========================================
    ) else (
        echo.
        echo ✗ Seeder failed! Please check the error messages above.
        echo You may need to run: npm install
    )
) else (
    echo.
    echo ✗ Database creation failed!
    echo Please check:
    echo - MySQL Server is running
    echo - Credentials are correct
    echo - User has permission to create database
)

echo.
pause
