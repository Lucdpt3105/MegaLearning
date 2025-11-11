@echo off
echo ========================================
echo  MEGALEARNING - SETUP CHECK
echo ========================================
echo.

echo [1/4] Checking Java...
java -version
if %errorlevel% neq 0 (
    echo ❌ Java NOT FOUND! Download Java 17 from: https://adoptium.net/
    pause
    exit /b 1
)
echo ✅ Java OK
echo.

echo [2/4] Checking Node.js...
node -v
if %errorlevel% neq 0 (
    echo ❌ Node.js NOT FOUND! Download from: https://nodejs.org/
    pause
    exit /b 1
)
echo ✅ Node.js OK
echo.

echo [3/4] Checking Maven...
mvn -version
if %errorlevel% neq 0 (
    echo ❌ Maven NOT FOUND! Download from: https://maven.apache.org/download.cgi
    pause
    exit /b 1
)
echo ✅ Maven OK
echo.

echo [4/4] Checking MySQL...
mysql --version
if %errorlevel% neq 0 (
    echo ⚠️  MySQL command not found (but might be installed)
    echo    Make sure MySQL is running on port 3306
) else (
    echo ✅ MySQL OK
)
echo.

echo ========================================
echo  ✅ ALL PREREQUISITES CHECKED!
echo ========================================
echo.
echo Next steps:
echo   1. Make sure MySQL is running
echo   2. Create database: CREATE DATABASE learning3;
echo   3. Run: setup-project.bat
echo.
pause
