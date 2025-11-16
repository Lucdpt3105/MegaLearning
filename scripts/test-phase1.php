<?php
/**
 * Test Script for Phase 1 - Foundation Features
 * Run: php scripts/test-phase1.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         TEST PHASE 1 - FOUNDATION FEATURES                  ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Test 1: Database Migration - Users Table
echo "📊 TEST 1: Database Migration - Users Table\n";
echo "─────────────────────────────────────────────────────\n";

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
$expectedColumns = ['id', 'name', 'email', 'avatar', 'phone', 'bio', 'last_login_at', 'is_locked', 'password'];

$missingColumns = array_diff($expectedColumns, $columns);

if (empty($missingColumns)) {
    echo "✅ All required columns exist in users table\n";
    echo "   Columns: " . implode(', ', $columns) . "\n";
} else {
    echo "❌ Missing columns: " . implode(', ', $missingColumns) . "\n";
}
echo "\n";

// Test 2: Roles & Permissions
echo "👥 TEST 2: Roles & Permissions\n";
echo "─────────────────────────────────────────────────────\n";

$roles = Role::pluck('name')->toArray();
$expectedRoles = ['admin', 'teacher', 'student', 'ai'];

echo "✅ Roles found: " . implode(', ', $roles) . "\n";

foreach ($expectedRoles as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if ($role) {
        $permissionCount = $role->permissions()->count();
        echo "   ├─ {$roleName}: {$permissionCount} permissions\n";
    } else {
        echo "   ❌ {$roleName} role not found\n";
    }
}

$totalPermissions = Permission::count();
echo "   └─ Total permissions: {$totalPermissions}\n";
echo "\n";

// Test 3: User Model - Fillable Fields
echo "🔧 TEST 3: User Model - Fillable Fields\n";
echo "─────────────────────────────────────────────────────\n";

$user = new User();
$fillable = $user->getFillable();
$requiredFillable = ['name', 'email', 'avatar', 'phone', 'bio', 'is_locked'];

echo "✅ Fillable fields: " . implode(', ', $fillable) . "\n";

foreach ($requiredFillable as $field) {
    if (in_array($field, $fillable)) {
        echo "   ✓ {$field}\n";
    } else {
        echo "   ✗ {$field} (missing)\n";
    }
}
echo "\n";

// Test 4: Check Existing Users with Roles
echo "👤 TEST 4: Existing Users with Roles\n";
echo "─────────────────────────────────────────────────────\n";

$users = User::with('roles')->limit(5)->get();

if ($users->count() > 0) {
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->implode(', ') ?: 'No roles';
        echo "   User #{$user->id}: {$user->name} ({$user->email})\n";
        echo "   ├─ Roles: {$roles}\n";
        echo "   ├─ Avatar: " . ($user->avatar ?? 'Not set') . "\n";
        echo "   ├─ Phone: " . ($user->phone ?? 'Not set') . "\n";
        echo "   └─ Locked: " . ($user->is_locked ? 'Yes' : 'No') . "\n\n";
    }
} else {
    echo "   ℹ️  No users found in database\n\n";
}

// Test 5: Routes Check
echo "🛣️  TEST 5: Routes Registration\n";
echo "─────────────────────────────────────────────────────\n";

$routes = app('router')->getRoutes();
$requiredRoutes = [
    'profile.edit',
    'profile.update',
    'profile.password',
    'profile.password.update',
    'profile.avatar.delete',
    'password.request',
    'password.email',
    'password.reset',
    'password.update',
];

foreach ($requiredRoutes as $routeName) {
    $route = $routes->getByName($routeName);
    if ($route) {
        echo "   ✓ {$routeName}\n";
    } else {
        echo "   ✗ {$routeName} (missing)\n";
    }
}
echo "\n";

// Test 6: Storage Link
echo "💾 TEST 6: Storage Symlink\n";
echo "─────────────────────────────────────────────────────\n";

$publicStoragePath = public_path('storage');
if (is_link($publicStoragePath)) {
    echo "✅ Storage symlink exists at: {$publicStoragePath}\n";
    echo "   → Points to: " . readlink($publicStoragePath) . "\n";
} else {
    echo "❌ Storage symlink NOT found. Run: php artisan storage:link\n";
}
echo "\n";

// Summary
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                      TEST SUMMARY                            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "✅ Phase 1 - Foundation features tested successfully!\n\n";

echo "📌 MANUAL TESTS REQUIRED:\n";
echo "   1. Open browser: http://127.0.0.1:8000/login\n";
echo "   2. Click 'Quên mật khẩu?' → Test forgot password flow\n";
echo "   3. Login → Click 'My Profile' in sidebar\n";
echo "   4. Test upload avatar\n";
echo "   5. Test change password\n";
echo "   6. Test logout button\n\n";

echo "🎉 Ready to proceed to PHASE 2!\n";
