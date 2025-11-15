@echo off
echo ============================================
echo Testing Badge Feature
echo ============================================
echo.

echo Step 1: Check database structure
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo 'Checking chat_room_members table...' . PHP_EOL; $columns = DB::select('DESCRIBE chat_room_members'); foreach($columns as $col) { echo '  - ' . $col->Field . ' (' . $col->Type . ')' . PHP_EOL; }"
echo.

echo Step 2: Test unread count
php scripts/test-badge-count.php
echo.

echo Step 3: Instructions for manual testing
echo ============================================
echo 1. Open http://localhost:8000/chat in Tab 1
echo 2. Select User A (e.g., student1@megalearning.local)
echo 3. Open http://localhost:8000/chat in Tab 2 (incognito)
echo 4. Select User B (e.g., student2@megalearning.local)
echo 5. User A creates a room or chats with User B
echo 6. User B should see a badge with unread count
echo 7. When User B clicks on the room, badge disappears
echo 8. When User B switches to another room and User A sends more messages, badge appears again
echo ============================================
echo.

pause
