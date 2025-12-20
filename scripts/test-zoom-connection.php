<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "   ZOOM API CONNECTION TEST\n";
echo "========================================\n\n";

try {
    $zoom = app(\App\Services\ZoomService::class);
    
    echo "✓ ZoomService initialized\n";
    echo "  Account ID: " . config('services.zoom.account_id') . "\n";
    echo "  Client ID: " . substr(config('services.zoom.client_id'), 0, 10) . "...\n\n";
    
    echo "Creating test meeting...\n";
    
    $result = $zoom->createMeeting([
        'topic' => 'Test Meeting - MegaLearning',
        'duration' => 30,
        'agenda' => 'This is a test meeting',
    ]);
    
    echo "\n✓ Meeting created successfully!\n\n";
    echo "Meeting Details:\n";
    echo "  Meeting ID: " . $result['meeting_id'] . "\n";
    echo "  Join URL: " . $result['meeting_url'] . "\n";
    echo "  Password: " . ($result['password'] ?? 'N/A') . "\n";
    echo "  Start URL: " . substr($result['start_url'], 0, 50) . "...\n";
    
    echo "\n========================================\n";
    echo "SUCCESS! Zoom API is working correctly.\n";
    echo "========================================\n";
    
} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n\n";
    echo "Please check:\n";
    echo "1. ZOOM_ACCOUNT_ID is correct\n";
    echo "2. ZOOM_CLIENT_ID is correct\n";
    echo "3. ZOOM_CLIENT_SECRET is correct\n";
    echo "4. Your Zoom app has 'meeting:write:admin' scope\n";
    echo "5. Your Zoom app is activated\n\n";
    
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    
    exit(1);
}
