<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;

class QuestionBankSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     * Creates 200 questions per subject:
     * - 50 Multiple Choice (12-13 per level)
     * - 50 Essay (12-13 per level)
     * - 50 True/False (12-13 per level)
     * - 50 Fill Blank (12-13 per level)
     * Evenly distributed across 4 Bloom levels
     */
    public function run(): void
    {
        $teacher = User::first();
        if (!$teacher) {
            $this->command->error('No user found. Please create a user first.');
            return;
        }

        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            $this->command->error('No subjects found. Please create subjects first.');
            return;
        }

        $this->command->info('Creating 200 questions per subject (50 MC + 50 Essay + 50 T/F + 50 Fill)...');

        foreach ($subjects as $subject) {
            $this->command->info("Processing subject: {$subject->name}");

            // Create 50 Multiple Choice Questions (12-13 per level)
            $this->createMultipleChoiceQuestions($subject, $teacher);
            
            // Create 50 Essay Questions (12-13 per level)
            $this->createEssayQuestions($subject, $teacher);
            
            // Create 50 True/False Questions (12-13 per level)
            $this->createTrueFalseQuestions($subject, $teacher);
            
            // Create 50 Fill Blank Questions (12-13 per level)
            $this->createFillBlankQuestions($subject, $teacher);
            
            $this->command->info("✓ Completed {$subject->name}: 200 questions created");
        }

        $this->command->info('Successfully created all questions!');
    }

    /**
     * Create 50 multiple choice questions (12-13 per Bloom level)
     */
    private function createMultipleChoiceQuestions($subject, $teacher)
    {
        $distribution = $this->distributeQuestions(50); // [13, 13, 12, 12]
        
        foreach ($distribution as $bloomLevel => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $questionNumber = array_sum(array_slice($distribution, 0, $bloomLevel - 1)) + $i;

                $question = Question::create([
                    'content' => $this->generateMCQuestion($subject->name, $bloomLevel, $questionNumber),
                    'type' => 'multiple_choice',
                    'subject_id' => $subject->id,
                    'topic_id' => null,
                    'created_by' => $teacher->id,
                    'difficulty' => $this->getDifficultyByLevel($bloomLevel),
                    'bloom_level' => $bloomLevel,
                    'in_question_bank' => true,
                    'points' => $this->getPointsByLevel($bloomLevel),
                    'explanation' => "Câu hỏi trắc nghiệm mức độ {$bloomLevel} - {$this->getBloomLevelName($bloomLevel)}",
                ]);

                // Create 4 answers for each MC question
                $answers = $this->generateAnswers($subject->name, $bloomLevel, $questionNumber, 'MC');
                foreach ($answers as $index => $answerText) {
                    Answer::create([
                        'question_id' => $question->id,
                        'content' => $answerText,
                        'is_correct' => $index === 0, // First answer is correct
                        'order' => $index + 1,
                    ]);
                }
            }
        }
    }

    /**
     * Create 100 essay questions (25 per Bloom level)
    /**
     * Create 50 essay questions (12-13 per Bloom level)
     */
    private function createEssayQuestions($subject, $teacher)
    {
        $distribution = $this->distributeQuestions(50);

        foreach ($distribution as $bloomLevel => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $questionNumber = array_sum(array_slice($distribution, 0, $bloomLevel - 1)) + $i;

                Question::create([
                    'content' => $this->generateEssayQuestion($subject->name, $bloomLevel, $questionNumber),
                    'type' => 'essay',
                    'subject_id' => $subject->id,
                    'topic_id' => null,
                    'created_by' => $teacher->id,
                    'difficulty' => $this->getDifficultyByLevel($bloomLevel),
                    'bloom_level' => $bloomLevel,
                    'in_question_bank' => true,
                    'points' => $this->getPointsByLevel($bloomLevel) * 2, // Essay worth more
                    'explanation' => "Câu hỏi tự luận mức độ {$bloomLevel} - {$this->getBloomLevelName($bloomLevel)}",
                ]);
            }
        }
    }

    /**
     * Create 50 true/false questions (12-13 per Bloom level)
     */
    private function createTrueFalseQuestions($subject, $teacher)
    {
        $distribution = $this->distributeQuestions(50);

        foreach ($distribution as $bloomLevel => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $questionNumber = array_sum(array_slice($distribution, 0, $bloomLevel - 1)) + $i;
                $isTrue = $i % 2 === 1; // Alternate true/false

                $question = Question::create([
                    'content' => $this->generateTrueFalseQuestion($subject->name, $bloomLevel, $questionNumber, $isTrue),
                    'type' => 'true_false',
                    'subject_id' => $subject->id,
                    'topic_id' => null,
                    'created_by' => $teacher->id,
                    'difficulty' => $this->getDifficultyByLevel($bloomLevel),
                    'bloom_level' => $bloomLevel,
                    'in_question_bank' => true,
                    'points' => $this->getPointsByLevel($bloomLevel),
                    'explanation' => "Câu hỏi đúng/sai mức độ {$bloomLevel} - {$this->getBloomLevelName($bloomLevel)}",
                ]);

                // Create True/False answers
                Answer::create([
                    'question_id' => $question->id,
                    'content' => 'Đúng',
                    'is_correct' => $isTrue,
                    'order' => 1,
                ]);

                Answer::create([
                    'question_id' => $question->id,
                    'content' => 'Sai',
                    'is_correct' => !$isTrue,
                    'order' => 2,
                ]);
            }
        }
    }

    /**
     * Create 50 fill blank questions (12-13 per Bloom level)
     */
    private function createFillBlankQuestions($subject, $teacher)
    {
        $distribution = $this->distributeQuestions(50);

        foreach ($distribution as $bloomLevel => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $questionNumber = array_sum(array_slice($distribution, 0, $bloomLevel - 1)) + $i;

                Question::create([
                    'content' => $this->generateFillBlankQuestion($subject->name, $bloomLevel, $questionNumber),
                    'type' => 'fill_blank',
                    'subject_id' => $subject->id,
                    'topic_id' => null,
                    'created_by' => $teacher->id,
                    'difficulty' => $this->getDifficultyByLevel($bloomLevel),
                    'bloom_level' => $bloomLevel,
                    'in_question_bank' => true,
                    'points' => $this->getPointsByLevel($bloomLevel),
                    'explanation' => "Câu hỏi điền khuyết mức độ {$bloomLevel} - {$this->getBloomLevelName($bloomLevel)}",
                ]);
            }
        }
    }

    /**
     * Distribute questions evenly across 4 levels
     * Example: 50 questions -> [13, 13, 12, 12]
     */
    private function distributeQuestions($total)
    {
        $base = intdiv($total, 4);
        $remainder = $total % 4;
        
        $distribution = [];
        for ($level = 1; $level <= 4; $level++) {
            $distribution[$level] = $base + ($level <= $remainder ? 1 : 0);
        }
        
        return $distribution;
    }

    /**
     * Generate True/False question content
     */
    private function generateTrueFalseQuestion($subjectName, $level, $number, $isTrue)
    {
        $templates = [
            1 => [ // Remember
                'Toán' => $isTrue ? "Phép tính cơ bản số {$number} là đúng." : "Phép tính cơ bản số {$number} cho kết quả sai.",
                'Lý' => $isTrue ? "Định luật vật lý số {$number} được phát biểu chính xác." : "Công thức số {$number} được viết sai.",
                'Hóa' => $isTrue ? "Công thức hóa học số {$number} là đúng." : "Ký hiệu hóa học số {$number} là sai.",
                'Sinh' => $isTrue ? "Cấu trúc sinh học số {$number} được mô tả đúng." : "Chức năng sinh học số {$number} được mô tả sai.",
                'default' => $isTrue ? "Khái niệm số {$number} trong {$subjectName} là đúng." : "Khái niệm số {$number} trong {$subjectName} là sai."
            ],
            2 => [ // Understand
                'Toán' => $isTrue ? "Giải thích về phương trình số {$number} là chính xác." : "Cách hiểu về phương trình số {$number} là không chính xác.",
                'Lý' => $isTrue ? "Nguyên lý hoạt động số {$number} được trình bày đúng." : "Cách giải thích định luật số {$number} là sai.",
                'Hóa' => $isTrue ? "Quá trình phản ứng số {$number} diễn ra như mô tả." : "Cơ chế phản ứng số {$number} không diễn ra như vậy.",
                'Sinh' => $isTrue ? "Cơ chế sinh học số {$number} hoạt động đúng như mô tả." : "Quá trình sinh học số {$number} không xảy ra theo cách này.",
                'default' => $isTrue ? "Giải thích về vấn đề số {$number} trong {$subjectName} là đúng." : "Cách hiểu về vấn đề số {$number} trong {$subjectName} là sai."
            ],
            3 => [ // Apply
                'Toán' => $isTrue ? "Áp dụng công thức số {$number} vào trường hợp này là chính xác." : "Cách áp dụng công thức số {$number} trong trường hợp này là sai.",
                'Lý' => $isTrue ? "Sử dụng định luật số {$number} để giải bài này là đúng." : "Áp dụng định luật số {$number} vào bài này là không phù hợp.",
                'Hóa' => $isTrue ? "Dự đoán sản phẩm phản ứng số {$number} là chính xác." : "Kết quả dự đoán cho phản ứng số {$number} là sai.",
                'Sinh' => $isTrue ? "Ứng dụng nguyên lý số {$number} vào trường hợp này là đúng." : "Cách ứng dụng kiến thức số {$number} này là không chính xác.",
                'default' => $isTrue ? "Vận dụng kiến thức số {$number} trong {$subjectName} vào trường hợp này là đúng." : "Cách vận dụng số {$number} trong {$subjectName} này là sai."
            ],
            4 => [ // Analyze
                'Toán' => $isTrue ? "Phân tích mối quan hệ trong bài toán số {$number} là chính xác." : "Cách phân tích bài toán số {$number} có sai sót.",
                'Lý' => $isTrue ? "So sánh các định luật trong trường hợp số {$number} là đúng." : "Đánh giá về hiện tượng số {$number} là không chính xác.",
                'Hóa' => $isTrue ? "Phân tích cơ chế phản ứng số {$number} là chính xác." : "Kết luận về phản ứng số {$number} là sai.",
                'Sinh' => $isTrue ? "Phân tích mối liên hệ trong hệ thống số {$number} là đúng." : "Đánh giá về quá trình số {$number} là không đúng.",
                'default' => $isTrue ? "Phân tích vấn đề số {$number} trong {$subjectName} là chính xác." : "Kết luận về vấn đề số {$number} trong {$subjectName} là sai."
            ]
        ];

        $subjectKey = $this->getSubjectKey($subjectName);
        return $templates[$level][$subjectKey] ?? $templates[$level]['default'];
    }

    /**
     * Generate Fill Blank question content
     */
    private function generateFillBlankQuestion($subjectName, $level, $number)
    {
        $templates = [
            1 => [ // Remember
                'Toán' => "Công thức cơ bản số {$number} trong toán học là ___.",
                'Lý' => "Định luật vật lý số {$number} có công thức là ___.",
                'Hóa' => "Ký hiệu hóa học của nguyên tố số {$number} là ___.",
                'Sinh' => "Cơ quan sinh học số {$number} có chức năng chính là ___.",
                'default' => "Khái niệm cơ bản số {$number} trong {$subjectName} là ___."
            ],
            2 => [ // Understand
                'Toán' => "Ý nghĩa của phương trình số {$number} trong thực tế là ___.",
                'Lý' => "Nguyên lý hoạt động của định luật số {$number} dựa trên ___.",
                'Hóa' => "Điều kiện để phản ứng số {$number} xảy ra là ___.",
                'Sinh' => "Cơ chế hoạt động của quá trình số {$number} phụ thuộc vào ___.",
                'default' => "Giải thích cho khái niệm số {$number} trong {$subjectName} là ___."
            ],
            3 => [ // Apply
                'Toán' => "Để giải bài toán số {$number}, ta cần áp dụng công thức ___.",
                'Lý' => "Trong tình huống số {$number}, ta sử dụng định luật ___.",
                'Hóa' => "Để thu được sản phẩm mong muốn trong phản ứng số {$number}, cần ___ làm xúc tác.",
                'Sinh' => "Ứng dụng thực tế của nguyên lý số {$number} là ___.",
                'default' => "Để giải quyết vấn đề số {$number} trong {$subjectName}, cần vận dụng ___."
            ],
            4 => [ // Analyze
                'Toán' => "Mối quan hệ giữa các yếu tố trong bài toán số {$number} là ___.",
                'Lý' => "So với định luật A, định luật số {$number} có điểm khác biệt là ___.",
                'Hóa' => "Yếu tố quyết định hiệu quả phản ứng số {$number} là ___.",
                'Sinh' => "Tác động chính của quá trình số {$number} đối với hệ thống là ___.",
                'default' => "Kết luận phân tích về vấn đề số {$number} trong {$subjectName} là ___."
            ]
        ];

        $subjectKey = $this->getSubjectKey($subjectName);
        return $templates[$level][$subjectKey] ?? $templates[$level]['default'];
    }

    /**
     * Generate multiple choice question content based on subject and level
     */
    private function generateMCQuestion($subjectName, $level, $number)
    {
        $templates = [
            1 => [ // Remember/Knowledge
                'Toán' => "Định nghĩa của khái niệm cơ bản số {$number} trong toán học là gì?",
                'Lý' => "Công thức vật lý cơ bản số {$number} được sử dụng để tính toán đại lượng nào?",
                'Hóa' => "Nguyên tố hóa học số {$number} có ký hiệu là gì?",
                'Sinh' => "Cấu trúc sinh học cơ bản số {$number} có chức năng chính là gì?",
                'default' => "Khái niệm cơ bản số {$number} trong môn {$subjectName} là gì?"
            ],
            2 => [ // Understand
                'Toán' => "Giải thích ý nghĩa của phương trình toán học số {$number} trong thực tế?",
                'Lý' => "Mô tả nguyên lý hoạt động của định luật vật lý số {$number}?",
                'Hóa' => "Giải thích quá trình phản ứng hóa học số {$number} xảy ra như thế nào?",
                'Sinh' => "Trình bày cơ chế hoạt động của quá trình sinh học số {$number}?",
                'default' => "Giải thích khái niệm số {$number} trong môn {$subjectName}?"
            ],
            3 => [ // Apply
                'Toán' => "Áp dụng công thức số {$number} để giải bài toán thực tế nào sau đây?",
                'Lý' => "Sử dụng định luật số {$number} để tính toán trường hợp nào dưới đây?",
                'Hóa' => "Vận dụng kiến thức phản ứng số {$number} để dự đoán sản phẩm nào?",
                'Sinh' => "Ứng dụng nguyên lý số {$number} vào tình huống thực tế nào?",
                'default' => "Vận dụng kiến thức số {$number} vào bài toán nào trong {$subjectName}?"
            ],
            4 => [ // Analyze
                'Toán' => "Phân tích mối quan hệ giữa các yếu tố trong bài toán số {$number}?",
                'Lý' => "So sánh và đối chiếu các định luật trong trường hợp số {$number}?",
                'Hóa' => "Đánh giá và phân tích cơ chế phản ứng số {$number}?",
                'Sinh' => "Phân tích mối liên hệ giữa các quá trình trong hệ thống số {$number}?",
                'default' => "Phân tích vấn đề số {$number} trong môn {$subjectName}?"
            ]
        ];

        $subjectKey = $this->getSubjectKey($subjectName);
        return $templates[$level][$subjectKey] ?? $templates[$level]['default'];
    }

    /**
     * Generate essay question content
     */
    private function generateEssayQuestion($subjectName, $level, $number)
    {
        $templates = [
            1 => [ // Remember
                'Toán' => "Liệt kê và mô tả các bước giải quyết dạng bài toán số {$number}.",
                'Lý' => "Trình bày các công thức và định luật liên quan đến chủ đề số {$number}.",
                'Hóa' => "Nêu các phản ứng hóa học cơ bản của nhóm chất số {$number}.",
                'Sinh' => "Mô tả cấu trúc và chức năng của hệ thống sinh học số {$number}.",
                'default' => "Trình bày kiến thức cơ bản về chủ đề số {$number} trong {$subjectName}."
            ],
            2 => [ // Understand
                'Toán' => "Giải thích chi tiết phương pháp giải bài toán số {$number} và lý do áp dụng.",
                'Lý' => "Phân tích và giải thích nguyên lý hoạt động của hiện tượng số {$number}.",
                'Hóa' => "Giải thích cơ chế và điều kiện xảy ra phản ứng số {$number}.",
                'Sinh' => "Trình bày và giải thích quá trình diễn ra trong hệ thống số {$number}.",
                'default' => "Giải thích chi tiết về vấn đề số {$number} trong {$subjectName}."
            ],
            3 => [ // Apply
                'Toán' => "Áp dụng kiến thức để giải quyết bài toán thực tế số {$number}. Trình bày chi tiết lời giải.",
                'Lý' => "Vận dụng các định luật để phân tích và giải quyết tình huống số {$number}.",
                'Hóa' => "Thiết kế thí nghiệm và dự đoán kết quả cho phản ứng số {$number}.",
                'Sinh' => "Đề xuất giải pháp ứng dụng kiến thức vào thực tế cho trường hợp số {$number}.",
                'default' => "Vận dụng lý thuyết để giải quyết vấn đề thực tế số {$number} trong {$subjectName}."
            ],
            4 => [ // Analyze
                'Toán' => "Phân tích, so sánh các phương pháp giải và đánh giá ưu nhược điểm cho bài toán số {$number}.",
                'Lý' => "Phân tích nguyên nhân, hệ quả và đưa ra kết luận cho hiện tượng số {$number}.",
                'Hóa' => "Phân tích cơ chế, so sánh các điều kiện và đánh giá hiệu quả phản ứng số {$number}.",
                'Sinh' => "Đánh giá mối quan hệ nhân quả và phân tích tác động của quá trình số {$number}.",
                'default' => "Phân tích chuyên sâu, đánh giá và đưa ra kết luận về vấn đề số {$number} trong {$subjectName}."
            ]
        ];

        $subjectKey = $this->getSubjectKey($subjectName);
        return $templates[$level][$subjectKey] ?? $templates[$level]['default'];
    }

    /**
     * Generate 4 answers for multiple choice questions
     */
    private function generateAnswers($subjectName, $level, $number, $type = 'MC')
    {
        $prefix = $this->getSubjectKey($subjectName);
        
        return [
            "Đáp án đúng cho câu {$type}-{$number} ({$prefix} L{$level})",
            "Đáp án sai A - câu {$type}-{$number} ({$prefix} L{$level})",
            "Đáp án sai B - câu {$type}-{$number} ({$prefix} L{$level})",
            "Đáp án sai C - câu {$type}-{$number} ({$prefix} L{$level})",
        ];
    }

    /**
     * Get difficulty based on Bloom level
     */
    private function getDifficultyByLevel($level)
    {
        return match($level) {
            1 => 'easy',
            2 => 'medium',
            3, 4 => 'hard',
            default => 'medium'
        };
    }

    /**
     * Get points based on Bloom level
     */
    private function getPointsByLevel($level)
    {
        return match($level) {
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            default => 2
        };
    }

    /**
     * Get Bloom level name in Vietnamese
     */
    private function getBloomLevelName($level)
    {
        return match($level) {
            1 => 'Nhận biết',
            2 => 'Hiểu',
            3 => 'Vận dụng',
            4 => 'Phân tích',
            default => 'Không xác định'
        };
    }

    /**
     * Get subject key for templates
     */
    private function getSubjectKey($subjectName)
    {
        $normalized = mb_strtolower($subjectName);
        
        if (str_contains($normalized, 'toán')) return 'Toán';
        if (str_contains($normalized, 'lý')) return 'Lý';
        if (str_contains($normalized, 'hóa')) return 'Hóa';
        if (str_contains($normalized, 'sinh')) return 'Sinh';
        
        return 'default';
    }
}
