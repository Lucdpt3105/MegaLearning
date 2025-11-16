#!/bin/bash

echo "========================================"
echo " MegaLearning - Branch A1 Setup Script"
echo "========================================"
echo ""

echo "[1/8] Installing Composer dependencies..."
composer install
if [ $? -ne 0 ]; then
    echo "ERROR: Composer install failed"
    exit 1
fi

echo ""
echo "[2/8] Installing NPM dependencies..."
npm install
if [ $? -ne 0 ]; then
    echo "ERROR: NPM install failed"
    exit 1
fi

echo ""
echo "[3/8] Copying .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo ".env file created"
else
    echo ".env file already exists"
fi

echo ""
echo "[4/8] Generating application key..."
php artisan key:generate
if [ $? -ne 0 ]; then
    echo "ERROR: Key generation failed"
    exit 1
fi

echo ""
echo "[5/8] Running migrations..."
php artisan migrate:fresh
if [ $? -ne 0 ]; then
    echo "ERROR: Migrations failed"
    exit 1
fi

echo ""
echo "[6/8] Running seeders..."
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=ClassRoomSeeder
php artisan db:seed --class=StudentSeeder
if [ $? -ne 0 ]; then
    echo "ERROR: Seeders failed"
    exit 1
fi

echo ""
echo "[7/8] Creating storage link..."
php artisan storage:link
if [ $? -ne 0 ]; then
    echo "ERROR: Storage link failed"
    exit 1
fi

echo ""
echo "[8/8] Building assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "ERROR: Asset build failed"
    exit 1
fi

echo ""
echo "========================================"
echo " Setup completed successfully!"
echo "========================================"
echo ""
echo "Login credentials:"
echo "  Teacher: ngocmai@example.com / password123"
echo "  Student: student1@example.com / password123"
echo ""
echo "To start the server:"
echo "  php artisan serve"
echo ""
