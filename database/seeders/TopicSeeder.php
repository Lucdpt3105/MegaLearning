<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Subject;
use App\Models\Question;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "📚 Creating topics for all subjects...\n";

        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            echo "⚠️  No subjects found! Please run SubjectSeeder first.\n";
            return;
        }

        $topicsData = [
            'Toán học' => ['Đại số', 'Hình học', 'Giải tích', 'Xác suất thống kê', 'Phương trình', 'Hàm số', 'Đạo hàm', 'Tích phân'],
            'Vật lý' => ['Cơ học', 'Nhiệt học', 'Điện học', 'Quang học', 'Động lực học', 'Dòng điện', 'Điện trường'],
            'Hóa học' => ['Hóa vô cơ', 'Hóa hữu cơ', 'Hóa phân tích', 'Nguyên tử', 'Phản ứng hóa học', 'Liên kết hóa học'],
            'Lập trình Web' => ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'Database', 'SQL', 'Routing', 'Controllers', 'Models'],
            'Cơ sở dữ liệu' => ['SQL', 'Database Design', 'NoSQL', 'Joins', 'Normalization', 'Indexes', 'Transactions'],
            'Tiếng Anh' => ['Grammar', 'Vocabulary', 'Reading', 'Writing', 'Tenses', 'Phrasal Verbs'],
            'Lịch sử' => ['Lịch sử Việt Nam', 'Lịch sử thế giới', 'Kháng chiến', 'Cách mạng'],
            'Địa lý' => ['Địa lý tự nhiên', 'Địa lý kinh tế', 'Khí hậu', 'Địa hình'],
        ];

        $totalTopics = 0;

        foreach ($subjects as $subject) {
            if (!isset($topicsData[$subject->name])) {
                continue;
            }

            $topics = $topicsData[$subject->name];

            foreach ($topics as $topicName) {
                Topic::create([
                    'name' => $topicName,
                    'subject_id' => $subject->id,
                    'description' => "Nội dung về {$topicName} trong môn {$subject->name}",
                ]);

                $totalTopics++;
            }
        }

        echo "\n🎉 Created {$totalTopics} topics!\n";

        // Now assign random topics to existing questions
        echo "\n🔗 Assigning topics to questions...\n";
        
        $questions = Question::all();
        $assignedCount = 0;

        foreach ($questions as $question) {
            // Get topics for this question's subject
            $subjectTopics = Topic::where('subject_id', $question->subject_id)
                ->inRandomOrder()
                ->first();

            if ($subjectTopics) {
                $question->topic_id = $subjectTopics->id;
                $question->save();
                $assignedCount++;
            }
        }

        echo "✅ Assigned topics to {$assignedCount} questions\n";
        
        echo "\n💡 Topics are now available for search functionality!\n";
        echo "   - Browse by subject\n";
        echo "   - Filter by topic\n";
        echo "   - Search questions by topic name\n";
    }
}
