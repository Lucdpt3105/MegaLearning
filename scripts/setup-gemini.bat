@echo off
echo =========================================
echo   SETUP GOOGLE GEMINI AI (MIEN PHI!)
echo =========================================
echo.
echo Buoc 1: Lay API Key tu Google AI Studio
echo ----------------------------------------
echo 1. Truy cap: https://makersuite.google.com/app/apikey
echo 2. Dang nhap bang Google account
echo 3. Click "Create API Key"
echo 4. Chon "Create API key in new project"
echo 5. Copy API key
echo.
echo MIEN PHI - Khong can the tin dung!
echo 60 requests/phut - Du de chat!
echo.
echo Buoc 2: Nhap API Key
echo ----------------------------------------
set /p APIKEY="Nhap API key cua ban: "

if "%APIKEY%"=="" (
    echo ERROR: Ban chua nhap API key!
    pause
    exit /b 1
)

echo.
echo Dang cap nhat .env file...

REM Backup .env
copy .env .env.backup >nul 2>&1

REM Update GEMINI_API_KEY and AI_PROVIDER in .env using PowerShell
powershell -Command "(Get-Content .env) -replace '^GEMINI_API_KEY=.*', 'GEMINI_API_KEY=%APIKEY%' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '^AI_PROVIDER=.*', 'AI_PROVIDER=gemini' | Set-Content .env"

echo.
echo =========================================
echo   SETUP THANH CONG!
echo =========================================
echo.
echo ✅ Gemini API key da duoc luu vao .env
echo ✅ AI_PROVIDER da duoc set thanh 'gemini'
echo ✅ File backup: .env.backup
echo.
echo Tiep theo, chay cac lenh sau:
echo.
echo   1. php artisan config:clear
echo   2. php test-gemini.php (de test)
echo   3. php artisan queue:work (neu chua chay)
echo.
echo Sau do vao http://localhost:8000/chat-demo va chat voi Gemini AI!
echo.
echo 🎉 MIEN PHI - Khong mat tien!
echo.
pause
