@echo off
echo ========================================
echo  MEGALEARNING - CHAT QUICK START
echo ========================================
echo.

echo [1/4] Checking database tables...
php artisan migrate:status | findstr chat

echo.
echo [2/4] Creating sample chat data...
php artisan db:seed --class=ChatSeeder

echo.
echo [3/4] Checking routes...
php artisan route:list --path=chat

echo.
echo ========================================
echo  CHAT IS READY!
echo ========================================
echo.
echo 📍 Web Interface: http://127.0.0.1:8000/chat
echo 📍 API Endpoint: http://127.0.0.1:8000/api/v1/chat
echo.
echo 📚 Full Guide: CHAT_SETUP_GUIDE.md
echo.
echo [4/4] Opening browser...
timeout /t 2 /nobreak >nul
start http://127.0.0.1:8000/chat
echo.
echo ✅ DONE! Happy chatting! 🎉
pause
