<?php

/**
 * Test Google Gemini API connection
 * Run: php test-gemini.php
 */

require __DIR__ . '/vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? '';

echo "=========================================\n";
echo "   GOOGLE GEMINI API TEST\n";
echo "=========================================\n\n";

// Check if API key exists
if (empty($apiKey)) {
    echo "❌ [ERROR] GEMINI_API_KEY is empty in .env file\n\n";
    echo "To fix this:\n";
    echo "1. Run: setup-gemini.bat\n";
    echo "2. Or get API key from: https://makersuite.google.com/app/apikey\n";
    echo "3. Add to .env: GEMINI_API_KEY=your-key-here\n\n";
    echo "💡 Gemini is FREE - No credit card needed!\n\n";
    exit(1);
}

echo "✓ API Key found: " . substr($apiKey, 0, 20) . "...\n";
echo "✓ Testing connection to Google Gemini...\n\n";

// Test API call - v1beta with gemini-2.5-flash (stable)
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Chào bạn! Hãy giới thiệu về bản thân bằng tiếng Việt trong 2-3 câu.']
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'topK' => 40,
        'topP' => 0.95,
        'maxOutputTokens' => 500, // Tăng lên 500
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
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
        echo "Error message: " . ($data['error']['message'] ?? 'Unknown error') . "\n";
        echo "Error code: " . ($data['error']['code'] ?? 'N/A') . "\n\n";
        
        if (isset($data['error']['status']) && $data['error']['status'] === 'INVALID_ARGUMENT') {
            echo "💡 Your API key might be invalid. Please:\n";
            echo "   1. Visit: https://makersuite.google.com/app/apikey\n";
            echo "   2. Create a new API key\n";
            echo "   3. Update .env file\n\n";
        }
    }
    exit(1);
}

$data = json_decode($response, true);

if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    echo "❌ [ERROR] Unexpected response format\n";
    echo "Response: $response\n\n";
    exit(1);
}

echo "=========================================\n";
echo "   ✅ SUCCESS! Gemini API is working!\n";
echo "=========================================\n\n";

echo "AI Response:\n";
echo "─────────────────────────────────────────\n";
echo $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
echo "─────────────────────────────────────────\n\n";

echo "Model: gemini-pro\n";
echo "Provider: Google AI (FREE!)\n";
echo "Rate limit: 60 requests/minute\n\n";

echo "✅ Gemini AI is ready to use in MegaLearning!\n";
echo "✅ Make sure:\n";
echo "   - AI_PROVIDER=gemini in .env\n";
echo "   - Queue worker is running: php artisan queue:work\n\n";

echo "🎉 FREE AI - No payment required!\n";
echo "=========================================\n";
