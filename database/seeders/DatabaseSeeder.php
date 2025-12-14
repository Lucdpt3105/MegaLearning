<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with FULL demo data.
     * 
     * Usage: php artisan db:seed
     * Or: php artisan db:seed --class=DatabaseSeeder
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting comprehensive database seeding...');
        
        // Step 1: Core Authentication & Authorization
        $this->command->info('📝 Step 1: Roles, Permissions & Users');
        $this->call([
            RolesAndPermissionsSeeder::class,  // Roles: admin, teacher, student
            UserSeeder::class,                  // Admin, Teacher, Student users
        ]);
        
        // Step 2: Educational Content
        $this->command->info('📚 Step 2: Subjects, Topics, Questions & Documents');
        $this->call([
            SubjectSeeder::class,              // Math, Physics, Chemistry, Web, Database
            TopicSeeder::class,                // Topics for each subject
            QuestionBankSeeder::class,         // 60+ questions across subjects
            DocumentSeeder::class,             // Sample documents (PDFs, PPTs, etc)
        ]);
        
        // Step 3: Class Management
        $this->command->info('🏫 Step 3: Classes & Students');
        $this->call([
            StudentSeeder::class,              // More students
            ClassRoomSeeder::class,            // Classes with enrollments
        ]);
        
        // Step 4: Exams & Assessments
        $this->command->info('📝 Step 4: Exams');
        $this->call([
            ExamSeeder::class,                 // Sample exams
        ]);
        
        // Step 5: Communication
        $this->command->info('💬 Step 5: Chat & Forum');
        $this->call([
            PrivateChatSeeder::class,          // Private chat rooms
            ChatSeeder::class,                 // Chat messages
            ForumSeeder::class,                // Forum discussions
        ]);
        
        // Step 6: Activity Logs & Rankings
        $this->command->info('📊 Step 6: Activity Logs & Rankings');
        $this->call([
            ActivityLogSeeder::class,          // Activity logs for statistics
            StudentRankingSeeder::class,       // Calculate student rankings
        ]);
        
        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('🎯 Demo Accounts:');
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['👑 Admin', 'admin@megalearning.com', 'password'],
                ['👨‍🏫 Teacher', 'teacher@megalearning.com', 'password'],
                ['🎓 Student', 'student@megalearning.com', 'password'],
            ]
        );
        $this->command->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('📊 Seeded Data Summary:');
        $this->command->line('   • Roles & Permissions');
        $this->command->line('   • Users (Admin, Teachers, Students)');
        $this->command->line('   • 5+ Subjects (Math, Physics, Chemistry, Web, DB)');
        $this->command->line('   • 25+ Topics');
        $this->command->line('   • 60+ Questions with answers');
        $this->command->line('   • 20+ Documents (fake file paths)');
        $this->command->line('   • Classes with enrollments');
        $this->command->line('   • Exams & submissions');
        $this->command->line('   • Chat rooms & messages');
        $this->command->line('   • Forum discussions');
        $this->command->info('');
        $this->command->info('🌐 Ready to test at: http://127.0.0.1:8000');
    }
}
