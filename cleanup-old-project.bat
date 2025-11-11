@echo off
echo ========================================
echo  CLEANING UP OLD LARAVEL PROJECT
echo ========================================
echo.

echo [1/3] Removing Laravel directories...
if exist "app" rmdir /s /q "app"
if exist "bootstrap" rmdir /s /q "bootstrap"
if exist "config" rmdir /s /q "config"
if exist "database" rmdir /s /q "database"
if exist "routes" rmdir /s /q "routes"
if exist "storage" rmdir /s /q "storage"
if exist "tests" rmdir /s /q "tests"
if exist "vendor" rmdir /s /q "vendor"
if exist "node_modules" rmdir /s /q "node_modules"
if exist "resources" rmdir /s /q "resources"
if exist "public\hot" del /q "public\hot"
if exist "public\index.php" del /q "public\index.php"
if exist "public\robots.txt" del /q "public\robots.txt"
if exist "public\images" rmdir /s /q "public\images"

echo [2/3] Removing Laravel files...
if exist "artisan" del /q "artisan"
if exist "composer.json" del /q "composer.json"
if exist "composer.lock" del /q "composer.lock"
if exist "phpunit.xml" del /q "phpunit.xml"
if exist ".env" del /q ".env"
if exist ".env.example" del /q ".env.example"
if exist "package.json" del /q "package.json"
if exist "package-lock.json" del /q "package-lock.json"
if exist "vite.config.js" del /q "vite.config.js"
if exist "tailwind.config.js" del /q "tailwind.config.js"
if exist "postcss.config.js" del /q "postcss.config.js"

echo [3/3] Removing old documentation and test files...
if exist "docs" rmdir /s /q "docs"
if exist "README.md" del /q "README.md"
if exist "SETUP_CHECKLIST.md" del /q "SETUP_CHECKLIST.md"
if exist "chat-start.bat" del /q "chat-start.bat"
if exist "create-users.bat" del /q "create-users.bat"
if exist "test-chat-api.ps1" del /q "test-chat-api.ps1"
if exist "test-chat-direct.php" del /q "test-chat-direct.php"
if exist "test-chat-no-auth.bat" del /q "test-chat-no-auth.bat"

echo.
echo ========================================
echo  CLEANUP COMPLETED!
echo ========================================
echo.
echo Remaining structure:
echo   - backend/   (Spring Boot)
echo   - frontend/  (React)
echo   - README_FULLSTACK.md
echo.
echo You can now:
echo   1. cd backend   then run: mvn spring-boot:run
echo   2. cd frontend  then run: npm install ^&^& npm run dev
echo.
pause
