<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? '';

echo "=========================================\n";
echo "   LIST GEMINI MODELS\n";
echo "=========================================\n\n";

if (empty($apiKey)) {
    echo "❌ No API key found\n";
    exit(1);
}

// List models
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ HTTP $httpCode\n";
    echo "$response\n";
    exit(1);
}

$data = json_decode($response, true);

echo "Available models:\n";
echo "==================\n\n";

if (isset($data['models'])) {
    foreach ($data['models'] as $model) {
        $name = $model['name'] ?? 'N/A';
        $displayName = $model['displayName'] ?? 'N/A';
        $description = $model['description'] ?? 'N/A';
        
        // Check if supports generateContent
        $methods = $model['supportedGenerationMethods'] ?? [];
        $supportsGenerate = in_array('generateContent', $methods);
        
        if ($supportsGenerate) {
            echo "✅ $name\n";
            echo "   Display: $displayName\n";
            echo "   Description: $description\n\n";
        }
    }
}
