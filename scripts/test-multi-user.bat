@echo off
echo ================================
echo    TEST MULTI-USER CHAT
echo ================================
echo.
echo Huong dan test:
echo.
echo 1. Mo Chrome tab 1 - Chon "Student A"
echo 2. Mo Chrome Incognito tab 2 - Chon "Student B"  
echo 3. Mo Firefox tab 3 - Chon "Teacher Nguyen"
echo.
echo Kiem tra:
echo - Moi tab hien dung ten user o header
echo - Moi tab chi thay rooms ma user do la member
echo - Tin nhan cua user hien tai nam ben PHAI (mau xanh)
echo - Tin nhan cua user khac nam ben TRAI (mau trang)
echo.
echo URL: http://localhost:8000/chat
echo.
pause
start chrome http://localhost:8000/chat
timeout /t 2 >nul
start chrome --incognito http://localhost:8000/chat
timeout /t 2 >nul
start firefox -private-window http://localhost:8000/chat
echo.
echo Da mo 3 tab test!
pause
