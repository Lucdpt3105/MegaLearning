<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ChatDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🗑️  Cleaning old chat data...');
        
        // Clear old chat data (in correct order due to foreign keys)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('chat_room_members')->truncate();
        DB::table('chat_messages')->truncate();
        DB::table('chat_rooms')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('👥 Creating demo users...');

        // Ensure roles exist
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create demo users (easy passwords for testing)
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );
        $admin->syncRoles(['admin']);

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name' => 'Teacher Nguyen',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );
        $teacher->syncRoles(['teacher']);

        $student1 = User::firstOrCreate(
            ['email' => 'student1@test.com'],
            [
                'name' => 'Alice Student',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );
        $student1->syncRoles(['student']);

        $student2 = User::firstOrCreate(
            ['email' => 'student2@test.com'],
            [
                'name' => 'Bob Student',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );
        $student2->syncRoles(['student']);

        $student3 = User::firstOrCreate(
            ['email' => 'student3@test.com'],
            [
                'name' => 'Charlie Student',
                'password' => Hash::make('123456'),
                'email_verified_at' => now()
            ]
        );
        $student3->syncRoles(['student']);

        $this->command->info('💬 Creating chat rooms...');

        // Room 1: General Discussion
        $room1 = ChatRoom::create([
            'room_name' => '🎓 General Discussion',
            'room_type' => 'group',
            'created_by' => $admin->id,
            'is_active' => true
        ]);

        // Add members to room 1
        $room1->members()->attach($admin->id, ['role' => 'admin', 'joined_at' => now()]);
        $room1->members()->attach($teacher->id, ['role' => 'member', 'joined_at' => now()]);
        $room1->members()->attach($student1->id, ['role' => 'member', 'joined_at' => now()]);
        $room1->members()->attach($student2->id, ['role' => 'member', 'joined_at' => now()]);
        $room1->members()->attach($student3->id, ['role' => 'member', 'joined_at' => now()]);

        // Room 2: Laravel Study Group
        $room2 = ChatRoom::create([
            'room_name' => '📚 Laravel Study Group',
            'room_type' => 'group',
            'created_by' => $teacher->id,
            'is_active' => true
        ]);

        $room2->members()->attach($teacher->id, ['role' => 'admin', 'joined_at' => now()]);
        $room2->members()->attach($student1->id, ['role' => 'member', 'joined_at' => now()]);
        $room2->members()->attach($student2->id, ['role' => 'member', 'joined_at' => now()]);

        // Room 3: Private Chat (Teacher & Student)
        $room3 = ChatRoom::create([
            'room_name' => '👤 Private: Teacher & Alice',
            'room_type' => 'private',
            'created_by' => $student1->id,
            'is_active' => true
        ]);

        $room3->members()->attach($teacher->id, ['role' => 'member', 'joined_at' => now()]);
        $room3->members()->attach($student1->id, ['role' => 'admin', 'joined_at' => now()]);

        $this->command->info('📝 Adding sample messages...');

        // Messages for Room 1
        $this->createMessage($room1->room_id, $admin->id, 'Welcome to MegaLearning chat! 🎉', now()->subHours(2));
        $this->createMessage($room1->room_id, $student1->id, 'Hi everyone! Excited to be here!', now()->subHours(2)->addMinutes(5));
        $this->createMessage($room1->room_id, $student2->id, 'Hello! Looking forward to learning together.', now()->subHours(2)->addMinutes(10));
        $this->createMessage($room1->room_id, $teacher->id, 'Great to see everyone here. Let\'s have a productive semester!', now()->subHours(1)->addMinutes(30));
        $this->createMessage($room1->room_id, $student3->id, 'Can someone explain the assignment?', now()->subMinutes(45));
        $this->createMessage($room1->room_id, $teacher->id, 'Sure! Check the documents section for details.', now()->subMinutes(30));

        // Messages for Room 2
        $this->createMessage($room2->room_id, $teacher->id, 'Welcome to Laravel Study Group! 📚', now()->subHours(1));
        $this->createMessage($room2->room_id, $student1->id, 'What topic are we covering today?', now()->subMinutes(50));
        $this->createMessage($room2->room_id, $teacher->id, 'We\'ll be discussing Eloquent relationships and migrations.', now()->subMinutes(45));
        $this->createMessage($room2->room_id, $student2->id, 'Perfect! I have some questions about hasMany.', now()->subMinutes(40));
        $this->createMessage($room2->room_id, $teacher->id, 'Feel free to ask! That\'s what we\'re here for.', now()->subMinutes(35));

        // Messages for Room 3
        $this->createMessage($room3->room_id, $student1->id, 'Hi Teacher, I need help with my project.', now()->subMinutes(20));
        $this->createMessage($room3->room_id, $teacher->id, 'Of course! What seems to be the issue?', now()->subMinutes(18));
        $this->createMessage($room3->room_id, $student1->id, 'I\'m having trouble with the authentication system.', now()->subMinutes(15));
        $this->createMessage($room3->room_id, $teacher->id, 'Let me check your code. Can you share the error?', now()->subMinutes(12));

        $this->command->newLine();
        $this->command->info('✅ Demo data created successfully!');
        $this->command->newLine();
        $this->command->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@test.com', '123456', 'Admin'],
                ['teacher@test.com', '123456', 'Teacher'],
                ['student1@test.com', '123456', 'Student (Alice)'],
                ['student2@test.com', '123456', 'Student (Bob)'],
                ['student3@test.com', '123456', 'Student (Charlie)'],
            ]
        );
        $this->command->newLine();
        $this->command->info('📊 Rooms Created: 3');
        $this->command->info('💬 Messages Created: 15');
        $this->command->newLine();
        $this->command->warn('🔐 All passwords: 123456');
        $this->command->info('🌐 Login at: http://localhost:8000/login');
        $this->command->info('💬 Chat at: http://localhost:8000/chat');
    }

    /**
     * Create a message with custom timestamp
     */
    private function createMessage($roomId, $userId, $text, $createdAt)
    {
        ChatMessage::create([
            'room_id' => $roomId,
            'user_id' => $userId,
            'message_text' => $text,
            'message_type' => 'text',
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);
    }
}
