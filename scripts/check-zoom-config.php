<?php

/**
 * Check Zoom configuration status
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "========================================\n";
echo "  ZOOM CONFIGURATION STATUS\n";
echo "========================================\n\n";

$accountId = config('services.zoom.account_id');
$clientId = config('services.zoom.client_id');
$clientSecret = config('services.zoom.client_secret');

// Check if configured
$isConfigured = !empty($accountId) && !empty($clientId) && !empty($clientSecret);

if ($isConfigured) {
    echo "✅ Zoom is CONFIGURED\n\n";
    
    echo "Account ID: " . substr($accountId, 0, 10) . "..." . substr($accountId, -5) . "\n";
    echo "Client ID: " . substr($clientId, 0, 10) . "..." . substr($clientId, -5) . "\n";
    echo "Client Secret: " . str_repeat('*', strlen($clientSecret)) . "\n\n";
    
    echo "Testing connection...\n";
    
    try {
        $zoomService = new App\Services\ZoomService();
        
        // Try to get access token
        $reflection = new ReflectionClass($zoomService);
        $method = $reflection->getMethod('getAccessToken');
        $method->setAccessible(true);
        $token = $method->invoke($zoomService);
        
        if ($token) {
            echo "✅ Connection successful!\n";
            echo "✅ Access token retrieved\n\n";
            
            echo "You can now:\n";
            echo "1. Create video calls from Teacher Dashboard\n";
            echo "2. Test creating a meeting: php scripts/test-zoom-meeting.php\n";
        } else {
            echo "❌ Failed to get access token\n";
            echo "Please check your credentials\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Connection failed!\n";
        echo "Error: " . $e->getMessage() . "\n\n";
        echo "Solutions:\n";
        echo "1. Verify credentials at https://marketplace.zoom.us\n";
        echo "2. Make sure app is Activated\n";
        echo "3. Check Scopes are added (meeting:write, meeting:read, user:read)\n";
    }
    
} else {
    echo "❌ Zoom is NOT configured\n\n";
    
    $missing = [];
    if (empty($accountId)) $missing[] = "ZOOM_ACCOUNT_ID";
    if (empty($clientId)) $missing[] = "ZOOM_CLIENT_ID";
    if (empty($clientSecret)) $missing[] = "ZOOM_CLIENT_SECRET";
    
    echo "Missing credentials:\n";
    foreach ($missing as $key) {
        echo "- $key\n";
    }
    
    echo "\nTo setup Zoom:\n";
    echo "1. Run: scripts\\setup-zoom.bat\n";
    echo "2. Or manually edit .env file\n";
    echo "3. Read guide: ZOOM_SETUP_GUIDE.md\n";
}

echo "\n========================================\n";
