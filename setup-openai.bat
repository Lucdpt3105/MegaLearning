@echo off
echo =========================================
echo   SETUP OPENAI API KEY
echo =========================================
echo.
echo Buoc 1: Lay API Key tu OpenAI
echo --------------------------------
echo 1. Truy cap: https://platform.openai.com/api-keys
echo 2. Dang nhap (hoac dang ky tai khoan mien phi)
echo 3. Click "Create new secret key"
echo 4. Copy API key (bat dau bang sk-...)
echo.
echo Buoc 2: Nhap API Key
echo --------------------------------
set /p APIKEY="Paste API key cua ban vao day: "

if "%APIKEY%"=="" (
    echo ERROR: Ban chua nhap API key!
    pause
    exit /b 1
)

echo.
echo Dang cap nhat .env file...

REM Backup .env
copy .env .env.backup >nul 2>&1

REM Update OPENAI_API_KEY in .env using PowerShell
powershell -Command "(Get-Content .env) -replace '^OPENAI_API_KEY=.*', 'OPENAI_API_KEY=%APIKEY%' | Set-Content .env"

echo.
echo =========================================
echo   SETUP THANH CONG!
echo =========================================
echo.
echo API Key da duoc luu vao .env
echo File backup: .env.backup
echo.
echo Tiep theo, chay cac lenh sau:
echo.
echo   1. php artisan config:clear
echo   2. php artisan queue:work
echo.
echo Sau do vao http://localhost:8000/chat-demo va test!
echo.
pause
