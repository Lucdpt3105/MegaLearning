@echo off
echo Installing Node.js dependencies...
npm install

echo.
echo Creating .env file...
if not exist .env (
    copy .env.example .env
    echo Created .env file. Please update with your settings!
) else (
    echo .env file already exists
)

echo.
echo ========================================
echo Setup complete!
echo ========================================
echo.
echo Next steps:
echo 1. Edit .env file with your settings
echo 2. Run: npm run seed (to create demo data)
echo 3. Run: npm run dev (to start server)
echo.
pause
