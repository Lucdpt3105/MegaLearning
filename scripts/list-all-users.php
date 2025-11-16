<?php
/**
 * List all users in the system
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "=" . str_repeat("=", 70) . "\n";
echo "  ALL USERS IN SYSTEM\n";
echo "=" . str_repeat("=", 70) . "\n\n";

$users = User::orderBy('id')->get(['id', 'name', 'email']);

echo "Total users: " . $users->count() . "\n\n";

foreach ($users as $user) {
    $role = 'user';
    if (str_contains($user->email, 'admin')) $role = 'admin';
    elseif (str_contains($user->email, 'teacher')) $role = 'teacher';
    elseif (str_contains($user->email, 'student')) $role = 'student';
    elseif (str_contains($user->email, 'guest')) $role = 'guest';
    elseif (str_contains($user->email, 'ai@')) $role = 'ai';
    
    printf("ID: %-3d | %-20s | %-35s | %s\n", 
        $user->id, 
        $user->name, 
        $user->email,
        strtoupper($role)
    );
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ All users are available for chat testing\n";
echo "=" . str_repeat("=", 70) . "\n";
