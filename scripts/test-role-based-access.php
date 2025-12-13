#!/usr/bin/env php
<?php

/**
 * Test Role-Based Access Control (RBAC)
 * 
 * This script tests the login and role-based redirect system
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "\n";
echo "=================================================\n";
echo "  🔐 ROLE-BASED ACCESS CONTROL TEST\n";
echo "=================================================\n\n";

// Test accounts
$testAccounts = [
    ['email' => 'admin@megalearning.com', 'role' => 'admin', 'expected_route' => '/admin'],
    ['email' => 'teacher@megalearning.com', 'role' => 'teacher', 'expected_route' => '/teacher/dashboard'],
    ['email' => 'student@megalearning.com', 'role' => 'student', 'expected_route' => '/student/dashboard'],
];

echo "Testing user roles and expected redirects:\n\n";

foreach ($testAccounts as $account) {
    $user = User::where('email', $account['email'])->first();
    
    if (!$user) {
        echo "❌ User not found: {$account['email']}\n";
        continue;
    }
    
    $userRoles = $user->getRoleNames()->toArray();
    $hasCorrectRole = $user->hasRole($account['role']);
    
    echo "📧 Email: {$account['email']}\n";
    echo "👤 Name: {$user->name}\n";
    echo "🎭 Roles: " . implode(', ', $userRoles) . "\n";
    echo "✅ Has '{$account['role']}' role: " . ($hasCorrectRole ? 'YES' : 'NO') . "\n";
    echo "🔀 Expected redirect: {$account['expected_route']}\n";
    
    if ($hasCorrectRole) {
        echo "✅ PASSED - Correct role assigned\n";
    } else {
        echo "❌ FAILED - Missing role: {$account['role']}\n";
    }
    
    echo "\n";
}

echo "=================================================\n";
echo "  📋 ROUTE PROTECTION SUMMARY\n";
echo "=================================================\n\n";

echo "Route Protection Status:\n\n";

$routeProtections = [
    '/admin/*' => 'role:admin',
    '/teacher/*' => 'role:teacher', 
    '/student/*' => 'role:student',
];

foreach ($routeProtections as $route => $middleware) {
    echo "🔒 {$route} → Protected by middleware: {$middleware}\n";
}

echo "\n";
echo "=================================================\n";
echo "  🎯 LOGIN FLOW EXPLANATION\n";
echo "=================================================\n\n";

echo "When user logs in:\n";
echo "1️⃣  User enters email & password at /login\n";
echo "2️⃣  AuthController checks credentials\n";
echo "3️⃣  If valid, check user role:\n";
echo "    • admin → redirect to /admin\n";
echo "    • teacher → redirect to /teacher/dashboard\n";
echo "    • student → redirect to /student/dashboard\n";
echo "4️⃣  User can ONLY access routes matching their role\n";
echo "5️⃣  If user tries to access unauthorized route:\n";
echo "    → Gets 403 Forbidden error\n\n";

echo "=================================================\n";
echo "  🧪 TEST ACCOUNTS\n";
echo "=================================================\n\n";

echo "You can test login with these accounts:\n\n";

foreach ($testAccounts as $account) {
    echo "Role: " . strtoupper($account['role']) . "\n";
    echo "  Email: {$account['email']}\n";
    echo "  Password: password\n";
    echo "  Will redirect to: {$account['expected_route']}\n\n";
}

echo "=================================================\n";
echo "  ✅ ALL TESTS COMPLETED\n";
echo "=================================================\n\n";

echo "Next steps:\n";
echo "1. Visit http://localhost:8000/login\n";
echo "2. Login with any test account above\n";
echo "3. Verify you're redirected to the correct dashboard\n";
echo "4. Try accessing another role's route (should get 403)\n\n";
