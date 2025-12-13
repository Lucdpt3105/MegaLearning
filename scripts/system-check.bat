@echo off
echo ========================================
echo   MEGALEARNING - SYSTEM CHECK
echo ========================================
echo.

echo [1/7] Checking PHP...
php --version | findstr "PHP"

echo.
echo [2/7] Checking Composer packages...
if exist "vendor\autoload.php" (
    echo ✅ Composer packages installed
) else (
    echo ❌ Composer packages NOT installed
    echo Run: composer install
)

echo.
echo [3/7] Checking Database connection...
php -r "require 'vendor/autoload.php'; $app = require 'bootstrap/app.php'; $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); try { echo '✅ Connected to: ' . DB::connection()->getDatabaseName() . PHP_EOL; } catch(Exception $e) { echo '❌ Error: ' . $e->getMessage() . PHP_EOL; }"

echo.
echo [4/7] Checking Password Reset...
php scripts\check-database.php | findstr "password_reset_tokens"

echo.
echo [5/7] Checking Zoom API...
php scripts\check-zoom-config.php | findstr "CONFIGURED"

echo.
echo [6/7] Checking Gemini AI...
php -r "require 'vendor/autoload.php'; $app = require 'bootstrap/app.php'; $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); $key = config('services.gemini.api_key'); echo ($key && strlen($key) > 10) ? '✅ Gemini API Key configured' . PHP_EOL : '⚠️ Gemini API Key not set' . PHP_EOL;"

echo.
echo [7/7] Checking Routes...
php artisan route:list --columns=Method,URI,Name | findstr "password"

echo.
echo ========================================
echo   CHECK COMPLETE
echo ========================================
echo.

echo Quick tests:
echo - Password Reset: php scripts\reset-user-password.php student@megalearning.com 12345678
echo - Zoom Meeting: php scripts\test-zoom-meeting.php
echo - API Test: Open Thunder Client in VS Code
echo.

pause
