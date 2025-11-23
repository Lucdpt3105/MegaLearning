@echo off
echo =========================================
echo   KIEM TRA BAO MAT API KEY
echo =========================================
echo.

echo [1/4] Kiem tra .env co trong .gitignore...
git check-ignore .env >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] .env da duoc ignore - An toan!
) else (
    echo [WARNING] .env CHUA duoc ignore - NGUY HIEM!
    echo Them ngay vao .gitignore!
)

echo.
echo [2/4] Kiem tra .env co trong git status...
git status --short | findstr ".env" | findstr /V ".env.example" >nul 2>&1
if %errorlevel% equ 0 (
    echo [WARNING] .env xuat hien trong git status - NGUY HIEM!
) else (
    echo [OK] .env khong xuat hien - An toan!
)

echo.
echo [3/4] Kiem tra .env.example co placeholder...
findstr /C:"your-gemini-api-key-here" .env.example >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] .env.example chi chua placeholder - An toan!
) else (
    echo [WARNING] .env.example co the chua API key that!
)

echo.
echo [4/4] Kiem tra file test co duoc ignore...
git check-ignore test-gemini.php >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] test-gemini.php da duoc ignore - An toan!
) else (
    echo [INFO] test-gemini.php chua duoc ignore
)

echo.
echo =========================================
echo   KET QUA CUOI CUNG
echo =========================================
git check-ignore .env >nul 2>&1
if %errorlevel% equ 0 (
    echo.
    echo    ✓ API KEY CUA BAN DA DUOC BAO VE!
    echo    ✓ Co the commit va push len GitHub an toan
    echo.
) else (
    echo.
    echo    ✗ CANH BAO: API KEY CO THE BI LO!
    echo    ✗ Hay kiem tra lai .gitignore
    echo.
)

pause
