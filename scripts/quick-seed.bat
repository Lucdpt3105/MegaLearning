@echo off
REM ============================================
REM Quick Seed - Only add more data without wiping
REM ============================================

echo.
echo ========================================
echo  Quick Seed (Add More Data)
echo ========================================
echo.
echo This will ADD more data without deleting existing records.
echo.

set /p confirm="Continue? (y/n): "
if /i not "%confirm%"=="y" exit /b 0

echo.
echo Running seeders...
php artisan db:seed --class=DatabaseSeeder

echo.
echo ========================================
echo  DONE!
echo ========================================
echo.
pause
