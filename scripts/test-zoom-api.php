<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Http;

// Load environment
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing Zoom API Connection...\n\n";

$accountId = env('ZOOM_ACCOUNT_ID');
$clientId = env('ZOOM_CLIENT_ID');
$clientSecret = env('ZOOM_CLIENT_SECRET');

echo "Account ID: " . substr($accountId, 0, 10) . "...\n";
echo "Client ID: " . substr($clientId, 0, 10) . "...\n";
echo "Client Secret: " . (strlen($clientSecret) > 0 ? 'Set (' . strlen($clientSecret) . ' chars)' : 'NOT SET') . "\n\n";

// Test 1: Get OAuth Token
echo "📡 Step 1: Getting OAuth Access Token...\n";
try {
    $response = Http::withBasicAuth($clientId, $clientSecret)
        ->asForm()
        ->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => $accountId,
        ]);

    if ($response->successful()) {
        $token = $response->json('access_token');
        echo "✅ Token obtained successfully!\n";
        echo "Token: " . substr($token, 0, 20) . "...\n\n";
        
        // Test 2: Get User Info
        echo "📡 Step 2: Getting User Information...\n";
        $userResponse = Http::withToken($token)
            ->get('https://api.zoom.us/v2/users/me');
        
        if ($userResponse->successful()) {
            $user = $userResponse->json();
            echo "✅ User info retrieved!\n";
            echo "Email: " . ($user['email'] ?? 'N/A') . "\n";
            echo "Type: " . ($user['type'] ?? 'N/A') . "\n";
            echo "Account ID: " . ($user['account_id'] ?? 'N/A') . "\n\n";
            
            // Test 3: Create Test Meeting
            echo "📡 Step 3: Creating Test Meeting...\n";
            $meetingResponse = Http::withToken($token)
                ->post('https://api.zoom.us/v2/users/me/meetings', [
                    'topic' => 'Test Meeting from MegaLearning',
                    'type' => 2, // Scheduled
                    'start_time' => now()->addHours(1)->format('Y-m-d\TH:i:s'),
                    'duration' => 30,
                    'timezone' => 'Asia/Ho_Chi_Minh',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => false,
                        'mute_upon_entry' => true,
                    ],
                ]);
            
            if ($meetingResponse->successful()) {
                $meeting = $meetingResponse->json();
                echo "✅ Meeting created successfully!\n";
                echo "Meeting ID: " . $meeting['id'] . "\n";
                echo "Join URL: " . $meeting['join_url'] . "\n";
                echo "Password: " . ($meeting['password'] ?? 'None') . "\n\n";
                
                // Delete test meeting
                echo "🗑️ Cleaning up: Deleting test meeting...\n";
                $deleteResponse = Http::withToken($token)
                    ->delete('https://api.zoom.us/v2/meetings/' . $meeting['id']);
                
                if ($deleteResponse->successful()) {
                    echo "✅ Test meeting deleted.\n";
                } else {
                    echo "⚠️ Could not delete test meeting (it will auto-expire)\n";
                }
                
                echo "\n🎉 ALL TESTS PASSED! Zoom API is working correctly.\n";
            } else {
                echo "❌ Failed to create meeting!\n";
                echo "Status: " . $meetingResponse->status() . "\n";
                echo "Error: " . $meetingResponse->body() . "\n";
            }
        } else {
            echo "❌ Failed to get user info!\n";
            echo "Status: " . $userResponse->status() . "\n";
            echo "Error: " . $userResponse->body() . "\n";
        }
    } else {
        echo "❌ Failed to get access token!\n";
        echo "Status: " . $response->status() . "\n";
        echo "Response: " . $response->body() . "\n\n";
        
        echo "🔧 Possible issues:\n";
        echo "1. Check if Account ID is correct\n";
        echo "2. Check if Client ID and Secret are correct\n";
        echo "3. Make sure your Zoom App is activated\n";
        echo "4. Check if you're using Server-to-Server OAuth app type\n";
        echo "5. Make sure the app has required scopes (meeting:write:admin, user:read:admin)\n";
    }
} catch (Exception $e) {
    echo "❌ Exception occurred: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
