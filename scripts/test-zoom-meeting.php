<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\ZoomService;

echo "=== Testing Zoom Meeting Creation ===\n\n";

try {
    $zoomService = new ZoomService();
    
    echo "✅ ZoomService initialized\n";
    echo "Account ID: " . config('services.zoom.account_id') . "\n";
    echo "Client ID: " . config('services.zoom.client_id') . "\n\n";
    
    // Test creating a meeting
    echo "📞 Creating a test Zoom meeting...\n";
    
    $meeting = $zoomService->createMeeting([
        'topic' => 'MegaLearning Test Meeting',
        'duration' => 30,
        'agenda' => 'Testing Zoom integration for MegaLearning platform',
        'start_time' => now()->addMinutes(5),
    ]);
    
    echo "\n✅ Meeting Created Successfully!\n\n";
    echo "Meeting Details:\n";
    echo "================\n";
    echo "Meeting ID: " . $meeting['meeting_id'] . "\n";
    echo "Join URL: " . $meeting['meeting_url'] . "\n";
    echo "Start URL (Host): " . $meeting['start_url'] . "\n";
    echo "Password: " . ($meeting['password'] ?? 'N/A') . "\n\n";
    
    // Test getting meeting details
    echo "📋 Fetching meeting details...\n";
    $details = $zoomService->getMeeting($meeting['meeting_id']);
    echo "✅ Meeting topic: " . $details['topic'] . "\n";
    echo "✅ Duration: " . $details['duration'] . " minutes\n";
    echo "✅ Start time: " . $details['start_time'] . "\n\n";
    
    // Cleanup - delete the test meeting
    echo "🗑️  Cleaning up - deleting test meeting...\n";
    $deleted = $zoomService->deleteMeeting($meeting['meeting_id']);
    
    if ($deleted) {
        echo "✅ Test meeting deleted successfully\n\n";
    }
    
    echo "=== All Tests Passed! ===\n";
    echo "Zoom integration is working correctly! 🎉\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
