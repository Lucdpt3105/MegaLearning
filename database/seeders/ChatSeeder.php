<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy hoặc tạo users
        $user1 = User::first();
        
        if (!$user1) {
            echo "⚠️  Không tìm thấy user nào. Vui lòng tạo user trước!\n";
            echo "Chạy: php artisan db:seed --class=UserSeeder\n";
            return;
        }

        echo "✅ Tạo chat rooms mẫu...\n";

        // Tạo General Discussion Room
        $generalRoom = ChatRoom::create([
            'room_name' => 'General Discussion',
            'room_type' => 'group',
            'created_by' => $user1->id,
            'is_active' => true,
        ]);

        // Thêm user1 làm admin
        $generalRoom->members()->attach($user1->id, [
            'role' => 'admin',
            'joined_at' => now()
        ]);

        // Tạo tin nhắn mẫu
        ChatMessage::create([
            'room_id' => $generalRoom->id,
            'user_id' => $user1->id,
            'message_text' => 'Welcome to General Discussion! 👋',
            'message_type' => 'text',
        ]);

        ChatMessage::create([
            'room_id' => $generalRoom->id,
            'user_id' => $user1->id,
            'message_text' => 'Feel free to chat about anything here.',
            'message_type' => 'text',
        ]);

        echo "✅ Room '{$generalRoom->room_name}' created (ID: {$generalRoom->id})\n";

        // Tạo Laravel Study Group
        $studyRoom = ChatRoom::create([
            'room_name' => 'Laravel Study Group',
            'room_type' => 'group',
            'created_by' => $user1->id,
            'is_active' => true,
        ]);

        $studyRoom->members()->attach($user1->id, [
            'role' => 'admin',
            'joined_at' => now()
        ]);

        ChatMessage::create([
            'room_id' => $studyRoom->id,
            'user_id' => $user1->id,
            'message_text' => 'Let\'s learn Laravel together! 🚀',
            'message_type' => 'text',
        ]);

        echo "✅ Room '{$studyRoom->room_name}' created (ID: {$studyRoom->id})\n";

        // Nếu có subject, tạo Subject Room
        $subject = \App\Models\Subject::first();
        if ($subject) {
            $subjectRoom = ChatRoom::create([
                'room_name' => $subject->subject_name . ' Discussion',
                'room_type' => 'subject',
                'subject_id' => $subject->subject_id,
                'created_by' => $user1->id,
                'is_active' => true,
            ]);

            $subjectRoom->members()->attach($user1->id, [
                'role' => 'admin',
                'joined_at' => now()
            ]);

            ChatMessage::create([
                'room_id' => $subjectRoom->id,
                'user_id' => $user1->id,
                'message_text' => 'Welcome to ' . $subject->subject_name . ' discussion room!',
                'message_type' => 'text',
            ]);

            echo "✅ Room '{$subjectRoom->room_name}' created (ID: {$subjectRoom->id})\n";
        }

        echo "\n🎉 Chat seeder completed!\n";
        echo "📍 Truy cập: http://127.0.0.1:8000/chat\n";
    }
}
