<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Checking and fixing notification URLs...\n\n";

// Get all notifications with URLs
$notifications = DB::table('notifications')->get();

$updatedCount = 0;
foreach ($notifications as $notification) {
    $data = json_decode($notification->data, true);
    
    if (isset($data['url'])) {
        $oldUrl = $data['url'];
        $newUrl = $oldUrl;
        
        // Fix /admin/user/ to /admin/users/
        $newUrl = str_replace('/admin/user/', '/admin/users/', $newUrl);
        
        // Remove /edit from the end
        $newUrl = preg_replace('/\/edit$/', '', $newUrl);
        
        if ($oldUrl !== $newUrl) {
            echo "📝 Updating: {$oldUrl} → {$newUrl}\n";
            
            $data['url'] = $newUrl;
            
            DB::table('notifications')
                ->where('id', $notification->id)
                ->update(['data' => json_encode($data)]);
            
            $updatedCount++;
        }
    }
}

echo "\n✅ Updated {$updatedCount} notifications\n";
echo "🎉 Done!\n";
