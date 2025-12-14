<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating activity logs...');

        $users = User::all();
        $now = Carbon::now();

        $actions = [
            'login' => ['description' => null, 'entity_type' => null],
            'login_failed' => ['description' => 'Invalid credentials', 'entity_type' => null],
            'view_course' => ['description' => 'Viewed course details', 'entity_type' => 'Course'],
            'view_exam' => ['description' => 'Viewed exam', 'entity_type' => 'Exam'],
            'submit_exam' => ['description' => 'Submitted exam', 'entity_type' => 'ExamSubmission'],
            'create_document' => ['description' => 'Created new document', 'entity_type' => 'Document'],
            'update_profile' => ['description' => 'Updated profile information', 'entity_type' => 'User'],
            'view_dashboard' => ['description' => 'Accessed dashboard', 'entity_type' => null],
            'send_message' => ['description' => 'Sent chat message', 'entity_type' => 'ChatMessage'],
            'join_class' => ['description' => 'Enrolled in class', 'entity_type' => 'ClassEnrollment'],
        ];

        $logs = [];

        // Tạo log cho 7 ngày qua
        for ($day = 0; $day < 7; $day++) {
            $date = $now->copy()->subDays($day);
            
            // Random 10-30 logs mỗi ngày
            $logsPerDay = rand(10, 30);
            
            for ($i = 0; $i < $logsPerDay; $i++) {
                $user = $users->random();
                $actionKey = array_rand($actions);
                $action = $actions[$actionKey];
                
                // Random thời gian trong ngày
                $timestamp = $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59));
                
                $logs[] = [
                    'user_id' => $user->id,
                    'action' => $actionKey,
                    'entity_type' => $action['entity_type'],
                    'entity_id' => $action['entity_type'] ? rand(1, 10) : null,
                    'description' => $action['description'],
                    'ip_address' => $this->randomIp(),
                    'user_agent' => $this->randomUserAgent(),
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        // Tạo thêm nhiều login logs cho 24h qua (để chart đẹp hơn)
        for ($hour = 0; $hour < 24; $hour++) {
            $logsInHour = rand(2, 8);
            
            for ($i = 0; $i < $logsInHour; $i++) {
                $user = $users->random();
                $timestamp = $now->copy()->subHours(24 - $hour)->addMinutes(rand(0, 59));
                
                $logs[] = [
                    'user_id' => $user->id,
                    'action' => rand(0, 10) < 9 ? 'login' : 'login_failed', // 90% success, 10% failed
                    'entity_type' => null,
                    'entity_id' => null,
                    'description' => null,
                    'ip_address' => $this->randomIp(),
                    'user_agent' => $this->randomUserAgent(),
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        // Insert tất cả logs
        foreach (array_chunk($logs, 100) as $chunk) {
            ActivityLog::insert($chunk);
        }

        $this->command->info('✅ Created ' . count($logs) . ' activity logs');
    }

    private function randomIp()
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }

    private function randomUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1',
        ];
        
        return $userAgents[array_rand($userAgents)];
    }
}
