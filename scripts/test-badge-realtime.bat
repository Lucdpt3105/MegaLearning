@echo off
echo ================================================
echo Testing Real-time Badge Updates
echo ================================================
echo.
echo Test scenario:
echo 1. Tab 1: Login as luck (student)
echo 2. Tab 2: Login as teacher
echo 3. From Tab 1 (luck): Send message to teacher
echo 4. Check Tab 2 (teacher): Badge should appear immediately
echo 5. From Tab 2 (teacher): Click on luck's chat
echo 6. Badge should disappear
echo 7. From another room in Tab 1 (luck): Send message
echo 8. Check Tab 2 (teacher): Badge should update without refresh
echo.
echo ================================================
echo Instructions:
echo ================================================
echo 1. Open http://localhost:8000/chat in TWO different tabs
echo 2. Tab 1: Click "Chọn người dùng" and select "luck" (student)
echo 3. Tab 2: Click "Chọn người dùng" and select a teacher account
echo 4. In Tab 1: Start a chat with teacher and send a message
echo 5. In Tab 2: Check if badge appears on the room WITHOUT refresh
echo 6. Click on the room in Tab 2 to open it
echo 7. Badge should disappear
echo 8. In Tab 1: Send another message or create new room
echo 9. Tab 2 should show badge update in real-time
echo.
echo ================================================
echo Opening browser...
echo ================================================
start http://localhost:8000/chat
timeout /t 2 /nobreak >nul
start http://localhost:8000/chat
echo.
echo Two browser tabs opened!
echo Follow the instructions above to test.
echo.
pause
