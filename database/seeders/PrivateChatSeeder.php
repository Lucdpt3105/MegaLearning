<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PrivateChatSeeder extends Seeder
{
    /**
     * Seed data for private chat feature
     */
    public function run(): void
    {
        $this->command->info('🔄 Setting up private chat demo data...');

        // Get or create users
        $users = $this->ensureUsersExist();
        
        // Create private chat rooms
        $this->createPrivateChats($users);
        
        $this->command->newLine();
        $this->command->info('✅ Private chat demo data created!');
        $this->showSummary($users);
    }

    /**
     * Ensure demo users exist
     */
    private function ensureUsersExist(): array
    {
        $this->command->info('👥 Ensuring users exist...');

        // Ensure roles
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@megalearning.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->syncRoles(['admin']);
        }

        // Teachers
        $teacher1 = User::firstOrCreate(
            ['email' => 'teacher@megalearning.com'],
            [
                'name' => 'Giáo Viên Nguyễn Văn A',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$teacher1->hasRole('teacher')) {
            $teacher1->syncRoles(['teacher']);
        }

        $teacher2 = User::firstOrCreate(
            ['email' => 'teacher2@megalearning.com'],
            [
                'name' => 'Giáo Viên Trần Thị B',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$teacher2->hasRole('teacher')) {
            $teacher2->syncRoles(['teacher']);
        }

        // Students
        $student1 = User::firstOrCreate(
            ['email' => 'student@megalearning.com'],
            [
                'name' => 'Học Sinh Lê Văn C',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$student1->hasRole('student')) {
            $student1->syncRoles(['student']);
        }

        $student2 = User::firstOrCreate(
            ['email' => 'student2@megalearning.com'],
            [
                'name' => 'Học Sinh Phạm Thị D',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$student2->hasRole('student')) {
            $student2->syncRoles(['student']);
        }

        $student3 = User::firstOrCreate(
            ['email' => 'student3@megalearning.com'],
            [
                'name' => 'Học Sinh Hoàng Văn E',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$student3->hasRole('student')) {
            $student3->syncRoles(['student']);
        }

        $student4 = User::firstOrCreate(
            ['email' => 'student4@megalearning.com'],
            [
                'name' => 'Học Sinh Vũ Thị F',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );
        if (!$student4->hasRole('student')) {
            $student4->syncRoles(['student']);
        }

        // AI User
        $ai = User::firstOrCreate(
            ['email' => 'ai@megalearning.local'],
            [
                'name' => 'Gemini AI',
                'password' => Hash::make('no-login'),
                'email_verified_at' => now()
            ]
        );

        return [
            'admin' => $admin,
            'teacher1' => $teacher1,
            'teacher2' => $teacher2,
            'student1' => $student1,
            'student2' => $student2,
            'student3' => $student3,
            'student4' => $student4,
            'ai' => $ai
        ];
    }

    /**
     * Create private chat rooms with messages
     */
    private function createPrivateChats(array $users): void
    {
        $this->command->info('💬 Creating private chat rooms...');

        // 1. Teacher 1 ↔️ Student 1 (Hỏi bài tập)
        $room1 = $this->createPrivateRoom(
            $users['student1'],
            $users['teacher1'],
            '👨‍🏫 Hỏi bài tập - ' . $users['student1']->name
        );
        
        $this->addMessage($room1, $users['student1'], 'Chào thầy, em có thắc mắc về bài tập tuần này ạ', now()->subHours(2));
        $this->addMessage($room1, $users['teacher1'], 'Chào em! Thầy nghe em hỏi nhé 😊', now()->subHours(2)->addMinutes(5));
        $this->addMessage($room1, $users['student1'], 'Em không hiểu cách giải bài 5 trong phần Eloquent Relationships ạ', now()->subHours(2)->addMinutes(10));
        $this->addMessage($room1, $users['teacher1'], 'À bài đó. Em cần hiểu về hasMany và belongsTo trước. Thầy gửi link tài liệu cho em nhé', now()->subHours(1)->addMinutes(45));
        $this->addMessage($room1, $users['student1'], 'Dạ em cảm ơn thầy! Em sẽ đọc và làm thử', now()->subHours(1)->addMinutes(40));

        // 2. Teacher 2 ↔️ Student 2 (Tư vấn học tập)
        $room2 = $this->createPrivateRoom(
            $users['teacher2'],
            $users['student2'],
            '📚 Tư vấn học tập - ' . $users['student2']->name
        );
        
        $this->addMessage($room2, $users['teacher2'], 'Em học thế nào rồi? Cô thấy em vắng mấy buổi gần đây', now()->subHours(3));
        $this->addMessage($room2, $users['student2'], 'Dạ em bị ốm nên nghỉ ạ. Giờ em đã khỏe rồi cô', now()->subHours(3)->addMinutes(30));
        $this->addMessage($room2, $users['teacher2'], 'Ừ được rồi. Em cần bổ túc phần nào không?', now()->subHours(3)->addMinutes(35));
        $this->addMessage($room2, $users['student2'], 'Dạ em muốn học lại phần API và Authentication ạ', now()->subMinutes(50));
        $this->addMessage($room2, $users['teacher2'], 'OK, chiều nay cô hẹn em nhé. 3h chiều được không?', now()->subMinutes(45));
        $this->addMessage($room2, $users['student2'], 'Dạ được ạ! Em cảm ơn cô 🙏', now()->subMinutes(40));

        // 3. Student 1 ↔️ Student 2 (Học nhóm)
        $room3 = $this->createPrivateRoom(
            $users['student1'],
            $users['student2'],
            '👥 Học nhóm - Lê Văn C & Phạm Thị D'
        );
        
        $this->addMessage($room3, $users['student1'], 'Hey! Mình chuẩn bị làm project chung không?', now()->subHours(1));
        $this->addMessage($room3, $users['student2'], 'Ừ nhỉ! Mình làm về topic gì nhỉ?', now()->subHours(1)->addMinutes(10));
        $this->addMessage($room3, $users['student1'], 'Mình nghĩ làm hệ thống E-Learning như thầy gợi ý', now()->subMinutes(55));
        $this->addMessage($room3, $users['student2'], 'Hay đấy! Mình chia task thế nào?', now()->subMinutes(50));
        $this->addMessage($room3, $users['student1'], 'Bạn làm backend, mình làm frontend nhé?', now()->subMinutes(45));
        $this->addMessage($room3, $users['student2'], 'OK! Mình bắt đầu từ đâu?', now()->subMinutes(40));

        // 4. Student 3 ↔️ Student 4 (Hỏi bài)
        $room4 = $this->createPrivateRoom(
            $users['student3'],
            $users['student4'],
            '📝 Hỏi bài - Hoàng Văn E & Vũ Thị F'
        );
        
        $this->addMessage($room4, $users['student3'], 'Bạn làm được bài tập Migration chưa?', now()->subMinutes(30));
        $this->addMessage($room4, $users['student4'], 'Chưa luôn 😅 Bài đó khó ghê', now()->subMinutes(25));
        $this->addMessage($room4, $users['student3'], 'Mình cũng vậy. Mình gặp lỗi foreign key hoài', now()->subMinutes(20));
        $this->addMessage($room4, $users['student4'], 'Để mình xem code của bạn. Gửi GitHub link đi', now()->subMinutes(15));

        // 5. Admin ↔️ Teacher 1 (Công việc)
        $room5 = $this->createPrivateRoom(
            $users['admin'],
            $users['teacher1'],
            '💼 Công việc - Admin & Teacher'
        );
        
        $this->addMessage($room5, $users['admin'], 'Thầy check email về lịch họp tuần sau chưa?', now()->subHours(4));
        $this->addMessage($room5, $users['teacher1'], 'Dạ anh ơi, em đã xem rồi. Em sẽ tham gia', now()->subHours(4)->addMinutes(20));
        $this->addMessage($room5, $users['admin'], 'OK. Nhớ chuẩn bị báo cáo về kết quả học tập lớp nhé', now()->subMinutes(60));
        $this->addMessage($room5, $users['teacher1'], 'Dạ vâng ạ. Em sẽ gửi trước ngày họp', now()->subMinutes(55));

        // 6. Teacher 1 ↔️ Student 3 (Nhận xét bài)
        $room6 = $this->createPrivateRoom(
            $users['student3'],
            $users['teacher1'],
            '✅ Nhận xét bài tập - ' . $users['student3']->name
        );
        
        $this->addMessage($room6, $users['student3'], 'Thầy ơi, em đã nộp bài tập rồi ạ', now()->subMinutes(90));
        $this->addMessage($room6, $users['teacher1'], 'Ừ thầy thấy rồi. Bài của em làm khá tốt đấy!', now()->subMinutes(80));
        $this->addMessage($room6, $users['student3'], 'Dạ cảm ơn thầy! Em có làm đúng hết không ạ?', now()->subMinutes(75));
        $this->addMessage($room6, $users['teacher1'], 'Phần database design của em rất tốt. Nhưng em cần optimize queries thêm', now()->subMinutes(70));
        $this->addMessage($room6, $users['student3'], 'Dạ em hiểu rồi. Em sẽ sửa lại ạ', now()->subMinutes(65));

        // 7. Student 2 ↔️ Student 3 (Chia sẻ tài liệu)
        $room7 = $this->createPrivateRoom(
            $users['student2'],
            $users['student3'],
            '📖 Chia sẻ tài liệu'
        );
        
        $this->addMessage($room7, $users['student2'], 'Bạn có slide bài Laravel không? Mình quên mất', now()->subMinutes(40));
        $this->addMessage($room7, $users['student3'], 'Có! Để mình gửi link Google Drive cho', now()->subMinutes(35));
        $this->addMessage($room7, $users['student2'], 'Cảm ơn bạn nhiều nha! 😊', now()->subMinutes(30));

        // 8. Teacher 2 ↔️ Student 4 (Động viên)
        $room8 = $this->createPrivateRoom(
            $users['teacher2'],
            $users['student4'],
            '💪 Động viên - ' . $users['student4']->name
        );
        
        $this->addMessage($room8, $users['teacher2'], 'Em đừng nản nhé. Cô thấy em đã tiến bộ nhiều rồi đấy!', now()->subMinutes(120));
        $this->addMessage($room8, $users['student4'], 'Dạ em cảm ơn cô. Nhưng em thấy mình học chậm hơn bạn bè', now()->subMinutes(115));
        $this->addMessage($room8, $users['teacher2'], 'Mỗi người một tốc độ học khác nhau. Quan trọng là em có cố gắng không', now()->subMinutes(110));
        $this->addMessage($room8, $users['student4'], 'Dạ em sẽ cố gắng hơn ạ! 💪', now()->subMinutes(105));

        $this->command->info('✅ Created 8 private chat rooms with messages');
    }

    /**
     * Create a private room between two users
     */
    private function createPrivateRoom(User $user1, User $user2, string $name = null): ChatRoom
    {
        // Check if room already exists
        $existingRoom = ChatRoom::where('room_type', 'private')
            ->whereHas('members', function($q) use ($user1) {
                $q->where('user_id', $user1->id);
            })
            ->whereHas('members', function($q) use ($user2) {
                $q->where('user_id', $user2->id);
            })
            ->first();

        if ($existingRoom) {
            return $existingRoom;
        }

        // Create new room
        $roomName = $name ?? "Private: {$user1->name} & {$user2->name}";
        
        $room = ChatRoom::create([
            'room_name' => $roomName,
            'room_type' => 'private',
            'created_by' => $user1->id,
            'is_active' => true
        ]);

        // Add both users as members
        $room->members()->attach($user1->id, [
            'role' => 'member',
            'joined_at' => now()
        ]);

        $room->members()->attach($user2->id, [
            'role' => 'member',
            'joined_at' => now()
        ]);

        return $room;
    }

    /**
     * Add a message to a room
     */
    private function addMessage(ChatRoom $room, User $user, string $text, $timestamp = null)
    {
        $timestamp = $timestamp ?? now();
        
        ChatMessage::create([
            'room_id' => $room->room_id,
            'user_id' => $user->id,
            'message_text' => $text,
            'message_type' => 'text',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }

    /**
     * Show summary
     */
    private function showSummary(array $users): void
    {
        $this->command->newLine();
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('         PRIVATE CHAT DEMO ACCOUNTS        ');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->newLine();

        $this->command->table(
            ['Email', 'Password', 'Role', 'Name'],
            [
                ['admin@megalearning.com', 'password', '🔴 Admin', $users['admin']->name],
                ['teacher@megalearning.com', 'password', '🔵 Teacher', $users['teacher1']->name],
                ['teacher2@megalearning.com', 'password', '🔵 Teacher', $users['teacher2']->name],
                ['student@megalearning.com', 'password', '🟢 Student', $users['student1']->name],
                ['student2@megalearning.com', 'password', '🟢 Student', $users['student2']->name],
                ['student3@megalearning.com', 'password', '🟢 Student', $users['student3']->name],
                ['student4@megalearning.com', 'password', '🟢 Student', $users['student4']->name],
            ]
        );

        $this->command->newLine();
        $this->command->info('📊 Statistics:');
        $this->command->info('   • Private Rooms: 8');
        $this->command->info('   • Total Messages: ~40');
        $this->command->info('   • User Types: Admin (1), Teachers (2), Students (4)');
        $this->command->newLine();
        $this->command->warn('🔐 All passwords: password');
        $this->command->newLine();
        $this->command->info('🌐 Access:');
        $this->command->info('   • Login: http://localhost:8000/login');
        $this->command->info('   • Chat: http://localhost:8000/chat');
        $this->command->newLine();
        $this->command->info('💡 Try:');
        $this->command->info('   1. Login as student@megalearning.com');
        $this->command->info('   2. Go to Chat → Users tab');
        $this->command->info('   3. Click on a teacher to start private chat');
        $this->command->info('   4. Click on another student to chat peer-to-peer');
        $this->command->newLine();
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
