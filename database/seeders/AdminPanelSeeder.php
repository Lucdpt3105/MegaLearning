<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminPanelSeeder extends Seeder
{
    /**
     * Seed admin panel specific data (categories, exams, questions, meetings, files)
     */
    public function run(): void
    {
        $this->command->info('🎨 Seeding Admin Panel Demo Data...');

        // 1. Categories (Course Categories)
        $this->command->info('📁 Creating categories...');
        $categories = [
            ['name' => 'Khoa học tự nhiên', 'description' => 'Các môn học về tự nhiên', 'slug' => 'khoa-hoc-tu-nhien', 'courses_count' => 12],
            ['name' => 'Khoa học xã hội', 'description' => 'Các môn học xã hội', 'slug' => 'khoa-hoc-xa-hoi', 'courses_count' => 8],
            ['name' => 'Công nghệ thông tin', 'description' => 'Lập trình và CNTT', 'slug' => 'cong-nghe-thong-tin', 'courses_count' => 15],
            ['name' => 'Ngoại ngữ', 'description' => 'Tiếng Anh và các ngôn ngữ', 'slug' => 'ngoai-ngu', 'courses_count' => 10],
            ['name' => 'Kinh tế', 'description' => 'Quản trị và kinh doanh', 'slug' => 'kinh-te', 'courses_count' => 6],
            ['name' => 'Nghệ thuật', 'description' => 'Âm nhạc, hội họa, nhiếp ảnh', 'slug' => 'nghe-thuat', 'courses_count' => 5],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'slug' => $cat['slug'],
                'courses_count' => $cat['courses_count'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Additional Exam Results (if exam_submissions table exists)
        if (DB::getSchemaBuilder()->hasTable('exam_submissions')) {
            $this->command->info('📝 Creating exam submissions...');
            
            $students = User::role('student')->take(5)->get();
            $exams = DB::table('exams')->take(3)->get();
            
            if ($students->count() > 0 && $exams->count() > 0) {
                foreach ($students as $student) {
                    foreach ($exams as $exam) {
                        DB::table('exam_submissions')->insertOrIgnore([
                            'exam_id' => $exam->id,
                            'student_id' => $student->id,
                            'attempt_number' => 1,
                            'score' => rand(50, 100),
                            'grading_status' => ['pending', 'graded', 'auto_graded'][rand(0, 2)],
                            'status' => 'submitted',
                            'started_at' => now()->subDays(rand(1, 10)),
                            'submitted_at' => now()->subDays(rand(0, 5)),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // 3. Forum Topics (if forum_threads table exists)
        if (DB::getSchemaBuilder()->hasTable('forum_threads')) {
            $this->command->info('💬 Creating forum threads...');
            
            $teachers = User::role('teacher')->take(2)->get();
            $subjects = DB::table('subjects')->take(3)->get();
            
            if ($teachers->count() > 0 && $subjects->count() > 0) {
                $topics = [
                    ['title' => 'Làm thế nào để học tốt Toán?', 'content' => 'Mọi người chia sẻ kinh nghiệm học Toán nhé!', 'views' => 234],
                    ['title' => 'Tips ôn thi Vật lý cuối kỳ', 'content' => 'Chia sẻ các bí quyết ôn thi Vật lý hiệu quả', 'views' => 156],
                    ['title' => 'Chia sẻ tài liệu Hóa học hay', 'content' => 'Topic tổng hợp tài liệu Hóa học chất lượng', 'views' => 189],
                    ['title' => 'Hỏi đáp về bài tập lập trình', 'content' => 'Giải đáp thắc mắc về lập trình', 'views' => 312],
                ];

                foreach ($topics as $topic) {
                    DB::table('forum_threads')->insertOrIgnore([
                        'title' => $topic['title'],
                        'content' => $topic['content'],
                        'subject_id' => $subjects->random()->id,
                        'created_by' => $teachers->random()->id,
                        'view_count' => $topic['views'],
                        'status' => 'active',
                        'is_pinned' => false,
                        'is_locked' => false,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 4. Video Calls / Meetings (if video_calls table exists)
        if (DB::getSchemaBuilder()->hasTable('video_calls')) {
            $this->command->info('📹 Creating video call records...');
            
            $teachers = User::role('teacher')->take(3)->get();
            if ($teachers->count() > 0) {
                $meetings = [
                    ['title' => 'Họp phụ huynh lớp 10A', 'participants' => 45],
                    ['title' => 'Hướng dẫn làm bài tập Toán', 'participants' => 32],
                    ['title' => 'Ôn thi cuối kỳ Vật lý', 'participants' => 28],
                ];

                foreach ($meetings as $meeting) {
                    DB::table('video_calls')->insertOrIgnore([
                        'title' => $meeting['title'],
                        'host_id' => $teachers->random()->id,
                        'meeting_url' => 'https://meet.megalearning.com/' . \Illuminate\Support\Str::random(10),
                        'scheduled_at' => now()->subDays(rand(1, 10)),
                        'started_at' => now()->subDays(rand(1, 10)),
                        'ended_at' => now()->subDays(rand(0, 5)),
                        'status' => 'completed',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 5. Documents / Files (if documents table exists)
        if (DB::getSchemaBuilder()->hasTable('documents')) {
            $this->command->info('📄 Creating document records...');
            
            $teachers = User::role('teacher')->take(3)->get();
            $subjects = DB::table('subjects')->take(3)->get();
            
            if ($teachers->count() > 0 && $subjects->count() > 0) {
                $files = [
                    ['name' => 'Đề cương ôn tập Toán.pdf', 'type' => 'pdf', 'size' => 2400000],
                    ['name' => 'Bài giảng Vật lý.pptx', 'type' => 'pptx', 'size' => 5300000],
                    ['name' => 'Đề thi thử Hóa học.docx', 'type' => 'docx', 'size' => 1200000],
                    ['name' => 'Video bài giảng Tiếng Anh.mp4', 'type' => 'mp4', 'size' => 48000000],
                ];

                foreach ($files as $file) {
                    DB::table('documents')->insertOrIgnore([
                        'title' => $file['name'],
                        'description' => 'Tài liệu học tập',
                        'file_name' => $file['name'],
                        'file_path' => 'documents/' . \Illuminate\Support\Str::random(20) . '.' . $file['type'],
                        'file_size' => $file['size'],
                        'file_type' => $file['type'],
                        'subject_id' => $subjects->random()->id,
                        'uploaded_by' => $teachers->random()->id,
                        'approval_status' => 'approved',
                        'approved_by' => $teachers->first()->id,
                        'approved_at' => now()->subDays(rand(1, 10)),
                        'created_at' => now()->subDays(rand(1, 20)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('');
        $this->command->info('✅ Admin Panel Demo Data Seeded Successfully!');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📊 Seeded Data:');
        $this->command->line('   • 4 Categories');
        $this->command->line('   • Exam submissions');
        $this->command->line('   • 4 Forum topics');
        $this->command->line('   • 3 Video call records');
        $this->command->line('   • 4 Document files');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
