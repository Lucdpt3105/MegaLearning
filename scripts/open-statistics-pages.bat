@echo off
echo ================================================
echo OPENING STATISTICS PAGES IN BROWSER
echo ================================================
echo.
echo Please make sure:
echo 1. PHP server is running: php artisan serve
echo 2. You are logged in as admin
echo.
echo Opening pages...
echo.

start http://127.0.0.1:8000/admin/statistics
timeout /t 2 /nobreak > nul

start http://127.0.0.1:8000/admin/statistics/students
timeout /t 2 /nobreak > nul

start http://127.0.0.1:8000/admin/statistics/rankings
timeout /t 2 /nobreak > nul

echo.
echo ✅ Opened all statistics pages!
echo.
echo Pages opened:
echo - Statistics Dashboard: http://127.0.0.1:8000/admin/statistics
echo - Student Statistics: http://127.0.0.1:8000/admin/statistics/students
echo - Rankings: http://127.0.0.1:8000/admin/statistics/rankings
echo.
pause
