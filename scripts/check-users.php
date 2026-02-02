<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING USERS IN DATABASE ===\n\n";

$users = \App\Models\User::with('roles')->get();

echo "Total users: " . $users->count() . "\n\n";

if ($users->isEmpty()) {
    echo "❌ No users found in database!\n";
    echo "You need to run: php artisan db:seed\n";
} else {
    echo "ID | Name                     | Email                          | Roles\n";
    echo "---|--------------------------|--------------------------------|----------\n";
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->implode(', ') ?: 'none';
        printf("%-3d| %-24s | %-30s | %s\n", 
            $user->id, 
            $user->name, 
            $user->email, 
            $roles
        );
    }
    
    echo "\n=== Checking for admin@megalearning.com ===\n";
    $admin = \App\Models\User::where('email', 'admin@megalearning.com')->first();
    if ($admin) {
        $roles = $admin->roles->pluck('name')->implode(', ') ?: 'none';
        echo "✅ Found: {$admin->name} (ID: {$admin->id}, Roles: {$roles})\n";
    } else {
        echo "❌ NOT FOUND: admin@megalearning.com\n";
        echo "\nAvailable users:\n";
        \App\Models\User::with('roles')->get()->each(function($u) {
            $roles = $u->roles->pluck('name')->implode(', ') ?: 'none';
            echo "  - {$u->email} ({$roles})\n";
        });
    }
}
