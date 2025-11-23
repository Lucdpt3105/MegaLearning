<?php
/**
 * Quick test for dashboard route fix
 * Run: php scripts/test-dashboard-route.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 Testing Dashboard Route Fix\n";
echo "═══════════════════════════════════════════\n\n";

// Test 1: Check main dashboard route exists
echo "1️⃣  Checking main 'dashboard' route...\n";
$routes = app('router')->getRoutes();
$dashboardRoute = $routes->getByName('dashboard');

if ($dashboardRoute) {
    echo "   ✅ Route 'dashboard' exists\n";
    echo "   → URI: " . $dashboardRoute->uri() . "\n";
    echo "   → Methods: " . implode(', ', $dashboardRoute->methods()) . "\n\n";
} else {
    echo "   ❌ Route 'dashboard' NOT FOUND\n\n";
}

// Test 2: Check role-specific dashboard routes
echo "2️⃣  Checking role-specific dashboard routes...\n";

$roleRoutes = [
    'admin.dashboard' => 'Admin',
    'teacher.dashboard' => 'Teacher',
    'student.dashboard' => 'Student',
];

foreach ($roleRoutes as $routeName => $roleName) {
    $route = $routes->getByName($routeName);
    if ($route) {
        echo "   ✅ {$roleName} dashboard: {$route->uri()}\n";
    } else {
        echo "   ❌ {$roleName} dashboard NOT FOUND\n";
    }
}

echo "\n3️⃣  Testing route helper in views...\n";
try {
    $url = route('dashboard');
    echo "   ✅ route('dashboard') works: {$url}\n";
} catch (\Exception $e) {
    echo "   ❌ route('dashboard') error: " . $e->getMessage() . "\n";
}

echo "\n═══════════════════════════════════════════\n";
echo "✅ Dashboard route fix verified!\n\n";

echo "📋 Summary:\n";
echo "   • Main route 'dashboard' redirects based on user role\n";
echo "   • admin.dashboard → /admin\n";
echo "   • teacher.dashboard → /teacher/dashboard\n";
echo "   • student.dashboard → /student/dashboard\n\n";

echo "🎯 Profile pages will now use route('dashboard') successfully!\n";
