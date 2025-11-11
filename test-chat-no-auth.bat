@echo off
echo ========================================
echo  TEST CHAT - NO AUTHENTICATION
echo ========================================
echo.

echo [1/3] Checking Guest User...
php artisan tinker --execute="echo User::find(1) ? 'Guest user exists!' : 'Guest user NOT found!';"

echo.
echo [2/3] Creating sample room...
php artisan tinker --execute="use App\Models\ChatRoom; if (!ChatRoom::where('room_name', 'Public Test Room')->exists()) { $room = ChatRoom::create(['room_name' => 'Public Test Room', 'room_type' => 'group', 'created_by' => 1, 'is_active' => true]); echo 'Room created: ID=' . $room->room_id; } else { echo 'Room already exists!'; }"

echo.
echo [3/3] Opening chat in browser...
timeout /t 2 /nobreak >nul
start http://127.0.0.1:8000/chat

echo.
echo ========================================
echo  DONE! 
echo ========================================
echo.
echo Access chat at: http://127.0.0.1:8000/chat
echo NO LOGIN REQUIRED!
echo.
echo Test API:
echo   GET  http://127.0.0.1:8000/api/v1/chat/rooms
echo   POST http://127.0.0.1:8000/api/v1/chat/rooms/1/messages
echo.
pause
