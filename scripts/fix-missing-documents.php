<?php

/**
 * Script để tạo file mẫu cho các documents trong database
 * Dùng khi database có records nhưng files thực tế không tồn tại
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

echo "🔍 Scanning documents...\n\n";

$documents = Document::all();
$missing = [];
$exists = [];

foreach ($documents as $doc) {
    $fullPath = storage_path('app/public/' . $doc->file_path);
    
    if (file_exists($fullPath)) {
        $exists[] = $doc;
        echo "✅ #{$doc->id}: {$doc->title} - FILE EXISTS\n";
    } else {
        $missing[] = $doc;
        echo "❌ #{$doc->id}: {$doc->title} - FILE MISSING\n";
        echo "   Path: {$doc->file_path}\n";
    }
}

echo "\n📊 Summary:\n";
echo "   Total documents: " . count($documents) . "\n";
echo "   Files exist: " . count($exists) . "\n";
echo "   Files missing: " . count($missing) . "\n\n";

if (count($missing) > 0) {
    echo "❓ Do you want to create dummy files for missing documents? (yes/no): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    
    if (strtolower($line) === 'yes' || strtolower($line) === 'y') {
        echo "\n🔨 Creating dummy files...\n\n";
        
        foreach ($missing as $doc) {
            $fullPath = storage_path('app/public/' . $doc->file_path);
            $directory = dirname($fullPath);
            
            // Create directory if not exists
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
                echo "📁 Created directory: {$directory}\n";
            }
            
            // Create dummy file content
            $content = "=== DUMMY FILE ===\n\n";
            $content .= "Document Title: {$doc->title}\n";
            $content .= "Document ID: {$doc->id}\n";
            $content .= "Created: " . now()->toDateTimeString() . "\n\n";
            $content .= "This is a placeholder file.\n";
            $content .= "Please replace with actual content.\n";
            
            // Write file
            file_put_contents($fullPath, $content);
            echo "✅ Created: {$fullPath}\n";
        }
        
        echo "\n✨ Done! Created " . count($missing) . " dummy files.\n";
        echo "⚠️  Remember: These are placeholder files. Upload real files through admin panel!\n";
    } else {
        echo "\n❌ Cancelled. No files created.\n";
    }
} else {
    echo "✅ All documents have files!\n";
}
