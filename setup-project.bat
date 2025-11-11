@echo off
echo ========================================
echo  MEGALEARNING - PROJECT SETUP
echo ========================================
echo.

echo [Step 1] Configuring Backend...
echo.
echo Please edit this file MANUALLY:
echo   backend\src\main\resources\application.properties
echo.
echo Update these values:
echo   - spring.datasource.password=YOUR_MYSQL_PASSWORD
echo   - jwt.secret=YOUR_SECRET_KEY_256_BITS_MIN
echo   - openai.api.key=YOUR_OPENAI_KEY (optional)
echo.
set /p ready="Have you updated application.properties? (y/n): "
if /i not "%ready%"=="y" (
    echo.
    echo ❌ Please update application.properties first!
    pause
    exit /b 1
)

echo.
echo [Step 2] Creating Database...
echo.
echo Run this SQL command in MySQL:
echo   CREATE DATABASE learning3;
echo.
set /p dbready="Have you created the database? (y/n): "
if /i not "%dbready%"=="y" (
    echo.
    echo ❌ Please create database 'learning3' first!
    pause
    exit /b 1
)

echo.
echo [Step 3] Building Backend...
cd backend
echo Running: mvn clean install
mvn clean install -DskipTests
if %errorlevel% neq 0 (
    echo ❌ Backend build failed!
    cd ..
    pause
    exit /b 1
)
echo ✅ Backend built successfully!
cd ..

echo.
echo [Step 4] Installing Frontend Dependencies...
cd frontend
echo Running: npm install
call npm install
if %errorlevel% neq 0 (
    echo ❌ Frontend dependencies installation failed!
    cd ..
    pause
    exit /b 1
)
echo ✅ Frontend dependencies installed!
cd ..

echo.
echo ========================================
echo  ✅ SETUP COMPLETE!
echo ========================================
echo.
echo To run the project:
echo.
echo   Option 1 - Run separately:
echo     Terminal 1: cd backend  ^&^& mvn spring-boot:run
echo     Terminal 2: cd frontend ^&^& npm run dev
echo.
echo   Option 2 - Use start script:
echo     Double-click: start-all.bat
echo.
pause
