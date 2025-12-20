<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Fix old URLs from /admin/user/ID/edit to /admin/users/ID
$count1 = DB::table('notifications')
    ->where('data', 'like', '%/admin/user/%/edit%')
    ->update([
        'data' => DB::raw("REPLACE(REPLACE(data, '/admin/user/', '/admin/users/'), '/edit', '')")
    ]);

echo "✅ Updated {$count1} notifications from /admin/user/ID/edit to /admin/users/ID\n";

// Fix any remaining /admin/users/ID/edit to /admin/users/ID
$count2 = DB::table('notifications')
    ->where('data', 'like', '%/admin/users/%/edit%')
    ->update([
        'data' => DB::raw("REPLACE(data, '/edit', '')")
    ]);

echo "✅ Updated {$count2} notifications from /admin/users/ID/edit to /admin/users/ID\n";

echo "\n🎉 Done! Total updated: " . ($count1 + $count2) . " notifications\n";
