<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$documentId = $argv[1] ?? 36;

$document = App\Models\Document::find($documentId);

if (!$document) {
    echo "❌ Document #{$documentId} NOT FOUND\n";
    exit(1);
}

echo "✅ Document #{$documentId} found:\n";
echo "   Title: {$document->title}\n";
echo "   File path: {$document->file_path}\n";
echo "   Approval status: {$document->approval_status}\n";
echo "   Subject ID: {$document->subject_id}\n";

$fullPath = storage_path('app/public/' . $document->file_path);
echo "   Full path: {$fullPath}\n";
echo "   File exists: " . (file_exists($fullPath) ? '✅ YES' : '❌ NO') . "\n";

// Check if current user can access
echo "\n📋 Debug info:\n";
echo "   Route: " . route('student.documents.download', $documentId) . "\n";
