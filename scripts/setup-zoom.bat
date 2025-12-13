@echo off
echo ========================================
echo   ZOOM API SETUP - MEGA LEARNING
echo ========================================
echo.

echo BUOC 1: Tao Zoom App
echo ------------------------------------
echo 1. Truy cap: https://marketplace.zoom.us/develop/create
echo 2. Chon "Server-to-Server OAuth"
echo 3. Dat ten app: MegaLearning Video Calls
echo 4. Click Create
echo.

echo BUOC 2: Lay Credentials
echo ------------------------------------
echo Vao tab "App Credentials", copy 3 gia tri sau:
echo - Account ID
echo - Client ID  
echo - Client Secret
echo.

echo BUOC 3: Them Scopes (Quyen)
echo ------------------------------------
echo Vao tab "Scopes", them 3 quyen sau:
echo - meeting:write:admin
echo - meeting:read:admin
echo - user:read:admin
echo Sau do click "Continue" va "Activate"
echo.

pause
echo.

echo BUOC 4: Nhap thong tin vao .env
echo ------------------------------------
set /p ACCOUNT_ID="Nhap ZOOM_ACCOUNT_ID: "
set /p CLIENT_ID="Nhap ZOOM_CLIENT_ID: "
set /p CLIENT_SECRET="Nhap ZOOM_CLIENT_SECRET: "

echo.
echo Dang cap nhat file .env...

REM Backup .env
copy .env .env.backup >nul

REM Update .env using PowerShell
powershell -Command "(Get-Content .env) -replace 'ZOOM_ACCOUNT_ID=.*', 'ZOOM_ACCOUNT_ID=%ACCOUNT_ID%' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'ZOOM_CLIENT_ID=.*', 'ZOOM_CLIENT_ID=%CLIENT_ID%' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'ZOOM_CLIENT_SECRET=.*', 'ZOOM_CLIENT_SECRET=%CLIENT_SECRET%' | Set-Content .env"

echo.
echo ✅ Da cap nhat .env thanh cong!
echo.

echo BUOC 5: Test Zoom API
echo ------------------------------------
echo Dang test ket noi...
echo.

php scripts/test-zoom-api.php

echo.
echo ========================================
echo   HOAN THANH!
echo ========================================
echo.
echo Ban co the:
echo 1. Tao video call tu Teacher Dashboard
echo 2. Chay test: php scripts/test-zoom-meeting.php
echo 3. Xem huong dan: ZOOM_SETUP_GUIDE.md
echo.

pause
