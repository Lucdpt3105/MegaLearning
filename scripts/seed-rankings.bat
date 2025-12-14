@echo off
echo.
echo ========================================
echo   Tao Du lieu Xep hang Hoc sinh
echo ========================================
echo.

REM Run the ranking seeder
php artisan db:seed --class=StudentRankingSeeder

echo.
echo ========================================
echo   Hoan thanh!
echo ========================================
echo.
echo Ban co the xem ket qua tai:
echo http://127.0.0.1:8000/admin/statistics/rankings
echo.
pause
