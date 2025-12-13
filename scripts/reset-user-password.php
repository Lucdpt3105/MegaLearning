<?php

/**
 * Quick script to reset user password
 * Usage: php scripts/reset-user-password.php email@example.com newpassword
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if ($argc < 3) {
    echo "Usage: php reset-user-password.php email newpassword\n";
    echo "Example: php reset-user-password.php admin@megalearning.com 12345678\n";
    exit(1);
}

$email = $argv[1];
$newPassword = $argv[2];

$user = App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found: $email\n";
    exit(1);
}

$user->password = Hash::make($newPassword);
$user->save();

echo "✅ Password reset successfully!\n";
echo "Email: $email\n";
echo "New password: $newPassword\n";
echo "User: {$user->name}\n";
echo "Role: " . $user->getRoleNames()->first() . "\n";
