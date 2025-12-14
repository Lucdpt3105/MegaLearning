@echo off
echo.
echo ========================================
echo   Tao Du lieu Demo cho Thong ke
echo ========================================
echo.

echo Tao ActivityLogs...
php artisan db:seed --class=ActivityLogSeeder

echo.
echo Tao Student Rankings...
php artisan db:seed --class=StudentRankingSeeder

echo.
echo ========================================
echo   Hoan thanh!
echo ========================================
echo.
echo So lieu hien tai:
php artisan tinker --execute="echo 'ActivityLogs: ' . App\Models\ActivityLog::count() . PHP_EOL; echo 'StudentRankings: ' . App\Models\StudentRanking::count() . PHP_EOL;"

echo.
echo Truy cap: http://127.0.0.1:8000/admin/statistics
echo.
pause
