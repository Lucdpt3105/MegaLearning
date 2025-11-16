@echo off
echo ========================================
echo  MegaLearning - Branch A1 Setup Script
echo ========================================
echo.

echo [1/8] Installing Composer dependencies...
call composer install
if errorlevel 1 goto error

echo.
echo [2/8] Installing NPM dependencies...
call npm install
if errorlevel 1 goto error

echo.
echo [3/8] Copying .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created
) else (
    echo .env file already exists
)

echo.
echo [4/8] Generating application key...
call php artisan key:generate
if errorlevel 1 goto error

echo.
echo [5/8] Running migrations...
call php artisan migrate:fresh
if errorlevel 1 goto error

echo.
echo [6/8] Running seeders...
call php artisan db:seed --class=RolesAndPermissionsSeeder
call php artisan db:seed --class=UserSeeder
call php artisan db:seed --class=SubjectSeeder
call php artisan db:seed --class=ClassRoomSeeder
call php artisan db:seed --class=StudentSeeder
if errorlevel 1 goto error

echo.
echo [7/8] Creating storage link...
call php artisan storage:link
if errorlevel 1 goto error

echo.
echo [8/8] Building assets...
call npm run build
if errorlevel 1 goto error

echo.
echo ========================================
echo  Setup completed successfully!
echo ========================================
echo.
echo Login credentials:
echo   Teacher: ngocmai@example.com / password123
echo   Student: student1@example.com / password123
echo.
echo To start the server:
echo   php artisan serve
echo.
pause
goto end

:error
echo.
echo ========================================
echo  ERROR: Setup failed!
echo ========================================
echo.
pause
exit /b 1

:end
