@echo off
echo ========================================
echo  MEGALEARNING - STARTING ALL SERVICES
echo ========================================
echo.

echo [1/2] Starting Backend (Spring Boot)...
echo Opening new terminal for backend...
start "MegaLearning Backend" cmd /k "cd /d %~dp0backend && echo Starting Spring Boot... && mvn spring-boot:run"

timeout /t 3 /nobreak > nul

echo [2/2] Starting Frontend (React)...
echo Opening new terminal for frontend...
start "MegaLearning Frontend" cmd /k "cd /d %~dp0frontend && echo Installing dependencies... && npm install && echo Starting React dev server... && npm run dev"

echo.
echo ========================================
echo  ✅ SERVICES STARTING!
echo ========================================
echo.
echo Two new terminal windows opened:
echo   1. Backend  - http://localhost:8080/api
echo   2. Frontend - http://localhost:5173
echo.
echo Wait for both to finish starting, then:
echo   - Open http://localhost:5173 in browser
echo   - Login with: student1@test.com / 123456
echo.
echo To stop: Close both terminal windows
echo.
pause
