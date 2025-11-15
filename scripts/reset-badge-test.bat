@echo off
echo Resetting all last_read_at to test badge from scratch...
echo.

php artisan tinker --execute="DB::table('chat_room_members')->update(['last_read_at' => null]); echo 'Reset complete!' . PHP_EOL;"

echo.
echo All last_read_at has been reset to NULL
echo Now you can test badge behavior from fresh login
echo.
pause
