@echo off
title MegaLearning - Full Stack Application
color 0A

echo.
echo ========================================
echo   MEGALEARNING - FULL STACK SETUP
echo ========================================
echo.
echo Backend: Node.js + Express.js
echo Frontend: React.js + Vite
echo Database: MySQL
echo.
echo ========================================
echo.

:MENU
echo.
echo [1] Start Backend Only
echo [2] Start Frontend Only  
echo [3] Start Backend + Frontend (2 windows)
echo [4] Install Dependencies (Backend + Frontend)
echo [5] Run Database Seeder
echo [6] Test API Connection
echo [7] Exit
echo.

set /p choice="Enter your choice (1-7): "

if "%choice%"=="1" goto START_BACKEND
if "%choice%"=="2" goto START_FRONTEND
if "%choice%"=="3" goto START_BOTH
if "%choice%"=="4" goto INSTALL_DEPS
if "%choice%"=="5" goto RUN_SEEDER
if "%choice%"=="6" goto TEST_API
if "%choice%"=="7" goto END

echo Invalid choice! Please try again.
goto MENU

:START_BACKEND
echo.
echo Starting Backend Server...
echo.
cd backend-nodejs
start "MegaLearning Backend" cmd /k "npm run dev"
echo Backend started in new window!
goto MENU

:START_FRONTEND
echo.
echo Starting Frontend Server...
echo.
cd frontend
start "MegaLearning Frontend" cmd /k "npm run dev"
echo Frontend started in new window!
goto MENU

:START_BOTH
echo.
echo Starting Backend and Frontend...
echo.
cd backend-nodejs
start "MegaLearning Backend" cmd /k "npm run dev"
echo Backend started!
timeout /t 2 /nobreak >nul
cd ..\frontend
start "MegaLearning Frontend" cmd /k "npm run dev"
echo Frontend started!
echo.
echo ========================================
echo Both servers are running!
echo Backend: http://localhost:8080
echo Frontend: http://localhost:5173
echo ========================================
echo.
goto MENU

:INSTALL_DEPS
echo.
echo Installing dependencies...
echo.
echo [1/2] Installing Backend dependencies...
cd backend-nodejs
call npm install
echo.
echo [2/2] Installing Frontend dependencies...
cd ..\frontend
call npm install
echo.
echo ========================================
echo All dependencies installed successfully!
echo ========================================
echo.
goto MENU

:RUN_SEEDER
echo.
echo Running database seeder...
echo.
cd backend-nodejs
call npm run seed
echo.
echo Seeder completed!
echo.
goto MENU

:TEST_API
echo.
echo Testing API connection...
echo.
cd backend-nodejs
call test-api.bat
goto MENU

:END
echo.
echo Thank you for using MegaLearning!
echo.
pause
exit
