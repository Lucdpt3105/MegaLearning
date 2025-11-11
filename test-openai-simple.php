<?php

/**
 * Simple script to test OpenAI API connection
 * Run: php test-openai-simple.php
 */

require __DIR__ . '/vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['OPENAI_API_KEY'] ?? '';

echo "=========================================\n";
echo "   OPENAI API CONNECTION TEST\n";
echo "=========================================\n\n";

// Check if API key exists
if (empty($apiKey)) {
    echo "❌ [ERROR] OPENAI_API_KEY is empty in .env file\n\n";
    echo "To fix this:\n";
    echo "1. Run: setup-openai.bat\n";
    echo "2. Or manually add your API key to .env\n\n";
    exit(1);
}

echo "✓ API Key found: " . substr($apiKey, 0, 15) . "...\n";
echo "✓ Testing connection to OpenAI...\n\n";

// Test API call
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => 'Say "Xin chào" in Vietnamese']
    ],
    'max_tokens' => 50
]));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ [ERROR] cURL error: $error\n\n";
    exit(1);
}

if ($httpCode !== 200) {
    echo "❌ [ERROR] HTTP $httpCode\n";
    echo "Response: $response\n\n";
    
    $data = json_decode($response, true);
    if (isset($data['error'])) {
        echo "Error message: " . $data['error']['message'] . "\n";
        echo "Error type: " . $data['error']['type'] . "\n\n";
        
        if ($data['error']['type'] === 'insufficient_quota') {
            echo "💡 You need to add credits to your OpenAI account:\n";
            echo "   Visit: https://platform.openai.com/settings/organization/billing/overview\n\n";
        }
    }
    exit(1);
}

$data = json_decode($response, true);

if (!isset($data['choices'][0]['message']['content'])) {
    echo "❌ [ERROR] Unexpected response format\n";
    echo "Response: $response\n\n";
    exit(1);
}

echo "=========================================\n";
echo "   ✅ SUCCESS! OpenAI API is working!\n";
echo "=========================================\n\n";

echo "AI Response: " . $data['choices'][0]['message']['content'] . "\n\n";
echo "Model: " . ($data['model'] ?? 'unknown') . "\n";
echo "Tokens used: " . ($data['usage']['total_tokens'] ?? 'unknown') . "\n\n";

echo "✅ You can now use AI chat in MegaLearning!\n";
echo "✅ Make sure queue worker is running: php artisan queue:work\n\n";

echo "=========================================\n";
