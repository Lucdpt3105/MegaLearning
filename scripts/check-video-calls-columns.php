<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking video_calls table structure...\n\n";

$columns = DB::select('SHOW COLUMNS FROM video_calls');

foreach ($columns as $col) {
    echo $col->Field . " | " . $col->Type . " | " . $col->Null . " | " . $col->Key . "\n";
}

echo "\n";
echo "Looking for 'password' column...\n";
$hasPassword = collect($columns)->contains('Field', 'password');
echo $hasPassword ? "✓ Password column EXISTS\n" : "✗ Password column NOT FOUND\n";
