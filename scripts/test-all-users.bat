@echo off
echo ========================================
echo   TEST MULTI-USER CHAT - ALL USERS
echo ========================================
echo.
echo Danh sach users co san:
echo.
echo  1. Guest User       (ID: 9)
echo  2. Admin User       (ID: 10)
echo  3. Teacher Nguyen   (ID: 11)
echo  4. Student A        (ID: 12)
echo  5. Student B        (ID: 13)
echo  6. Student C        (ID: 14)
echo  7. Luck             (ID: 3)
echo  8. Gemini AI        (ID: 8)
echo.
echo Test Scenarios:
echo.
echo [1] Test Group Chat (3 users)
echo     - Tab 1: Student A
echo     - Tab 2: Student B  
echo     - Tab 3: Teacher Nguyen
echo.
echo [2] Test Private Chat Matrix
echo     - Student A chat voi Student B
echo     - Student A chat voi Teacher Nguyen
echo     - Student B chat voi Student C
echo.
echo [3] Test Isolation
echo     - Moi user chi thay rooms cua minh
echo     - Private chat chi 2 nguoi thay
echo.
echo ========================================
echo.
set /p choice="Chon test scenario (1/2/3): "

if "%choice%"=="1" goto test_group
if "%choice%"=="2" goto test_private
if "%choice%"=="3" goto test_isolation
goto end

:test_group
echo.
echo Mo 3 tabs cho Group Chat test...
start chrome "http://localhost:8000/chat"
timeout /t 2 >nul
start chrome --new-window "http://localhost:8000/chat"
timeout /t 2 >nul
start chrome --new-window "http://localhost:8000/chat"
echo.
echo Huong dan:
echo 1. Tab 1: Chon "Student A"
echo 2. Tab 2: Chon "Student B"
echo 3. Tab 3: Chon "Teacher Nguyen"
echo 4. Tab 1: Tao phong "Study Group"
echo 5. Tab 1: Vao tab "Nguoi Dung" - Add Student B va Teacher vao group
echo 6. Kiem tra: Ca 3 deu thay phong va chat duoc
goto end

:test_private
echo.
echo Mo 4 tabs cho Private Chat test...
start chrome "http://localhost:8000/chat"
timeout /t 2 >nul
start chrome --new-window "http://localhost:8000/chat"
timeout /t 2 >nul
start chrome --new-window "http://localhost:8000/chat"
timeout /t 2 >nul
start chrome --new-window "http://localhost:8000/chat"
echo.
echo Huong dan:
echo 1. Tab 1: Chon "Student A"
echo 2. Tab 2: Chon "Student B"
echo 3. Tab 3: Chon "Teacher Nguyen"
echo 4. Tab 4: Chon "Student C"
echo.
echo Test Private Chat:
echo - Tab 1 (A): Chat voi B
echo - Tab 1 (A): Chat voi Teacher
echo - Tab 2 (B): Chi thay chat voi A
echo - Tab 3 (Teacher): Chi thay chat voi A
echo - Tab 4 (C): Khong thay chat nao
goto end

:test_isolation
echo.
echo Mo 5 tabs cho Isolation test...
start chrome "http://localhost:8000/chat"
timeout /t 1 >nul
start chrome --new-window "http://localhost:8000/chat"
timeout /t 1 >nul
start chrome --new-window "http://localhost:8000/chat"
timeout /t 1 >nul
start chrome --new-window "http://localhost:8000/chat"
timeout /t 1 >nul
start chrome --new-window "http://localhost:8000/chat"
echo.
echo Huong dan:
echo 1. Moi tab chon 1 user khac nhau
echo 2. Tao phong chat rieng o moi tab
echo 3. Kiem tra: Moi user chi thay phong cua minh
goto end

:end
echo.
echo ========================================
echo ✅ Test completed!
echo ========================================
pause
