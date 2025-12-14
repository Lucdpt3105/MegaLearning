@echo off
echo.
echo ========================================
echo   Kiem tra He thong Thong ke
echo ========================================
echo.

echo Kiem tra so lieu database...
php artisan tinker --execute="echo 'Users: ' . App\Models\User::count() . PHP_EOL; echo 'ActivityLogs: ' . App\Models\ActivityLog::count() . PHP_EOL; echo 'ExamSubmissions: ' . App\Models\ExamSubmission::count() . PHP_EOL; echo 'StudentRankings: ' . App\Models\StudentRanking::count() . PHP_EOL; echo 'Subjects: ' . App\Models\Subject::count() . PHP_EOL; echo 'ClassRooms: ' . App\Models\ClassRoom::count() . PHP_EOL;"

echo.
echo Danh sach routes thong ke:
php artisan route:list --path=statistics

echo.
echo ========================================
echo   Hoan thanh kiem tra!
echo ========================================
echo.
echo Truy cap cac trang sau de xem:
echo - http://127.0.0.1:8000/admin/statistics
echo - http://127.0.0.1:8000/admin/statistics/students
echo - http://127.0.0.1:8000/admin/statistics/rankings
echo - http://127.0.0.1:8000/admin/statistics/participation
echo - http://127.0.0.1:8000/admin/statistics/activity-logs
echo - http://127.0.0.1:8000/admin/statistics/usage-duration
echo.
pause
