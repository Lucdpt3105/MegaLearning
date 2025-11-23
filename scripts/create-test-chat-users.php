<?php
/**
 * Script to create test users for multi-user chat testing
 * Run: php scripts/create-test-chat-users.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🔧 Creating test users for chat system...\n\n";

$testUsers = [
    [
        'name' => 'Guest User',
        'email' => 'guest@megalearning.local',
        'password' => 'password123',
        'role' => 'guest'
    ],
    [
        'name' => 'Admin User',
        'email' => 'admin@megalearning.local',
        'password' => 'password123',
        'role' => 'admin'
    ],
    [
        'name' => 'Teacher Nguyen',
        'email' => 'teacher@megalearning.local',
        'password' => 'password123',
        'role' => 'teacher'
    ],
    [
        'name' => 'Student A',
        'email' => 'student1@megalearning.local',
        'password' => 'password123',
        'role' => 'student'
    ],
    [
        'name' => 'Student B',
        'email' => 'student2@megalearning.local',
        'password' => 'password123',
        'role' => 'student'
    ],
    [
        'name' => 'Student C',
        'email' => 'student3@megalearning.local',
        'password' => 'password123',
        'role' => 'student'
    ],
];

$createdCount = 0;
$existingCount = 0;

foreach ($testUsers as $userData) {
    $existingUser = User::where('email', $userData['email'])->first();
    
    if ($existingUser) {
        echo "⏭️  User already exists: {$userData['name']} ({$userData['email']})\n";
        echo "   ID: {$existingUser->id}\n\n";
        $existingCount++;
    } else {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'email_verified_at' => now(),
        ]);
        
        echo "✅ Created user: {$userData['name']} ({$userData['email']})\n";
        echo "   ID: {$user->id}\n";
        echo "   Password: {$userData['password']}\n\n";
        $createdCount++;
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 Summary:\n";
echo "   - Created: {$createdCount} users\n";
echo "   - Already existed: {$existingCount} users\n";
echo "   - Total: " . ($createdCount + $existingCount) . " users\n\n";

echo "🎯 Test users for chat:\n\n";
echo "   1. Guest User        - guest@megalearning.local\n";
echo "   2. Admin User        - admin@megalearning.local\n";
echo "   3. Teacher Nguyen    - teacher@megalearning.local\n";
echo "   4. Student A         - student1@megalearning.local\n";
echo "   5. Student B         - student2@megalearning.local\n";
echo "   6. Student C         - student3@megalearning.local\n\n";

echo "   All passwords: password123\n\n";

echo "🚀 Ready to test multi-user chat at: http://localhost:8000/chat\n";
echo "   - Open multiple browser tabs/windows\n";
echo "   - Select different users in each tab\n";
echo "   - Test group chat and private chat\n\n";

echo "✅ Done!\n";
