<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class QuestionsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $subjectId;

    public function __construct($subjectId)
    {
        $this->subjectId = $subjectId;
    }

    /**
     * Specify which row contains the headings
     */
    public function headingRow(): int
    {
        return 7; // Row 7 contains the actual column headers (after 6 instruction rows)
    }

    public function model(array $row)
    {
        // Skip if no content
        if (empty($row['noi_dung_cau_hoi'])) {
            return null;
        }

        // Find topic by name
        $topic = null;
        if (!empty($row['chuong_bai'])) {
            $topic = Topic::where('subject_id', $this->subjectId)
                ->where('name', 'like', '%' . $row['chuong_bai'] . '%')
                ->first();
        }

        // Map question type
        $type = $this->mapQuestionType($row['loai_cau_hoi'] ?? 'Trắc nghiệm');
        
        // Map bloom level
        $bloomLevel = $this->mapBloomLevel($row['muc_do_bloom'] ?? 'Nhận biết');
        
        // Map difficulty
        $difficulty = $this->mapDifficulty($row['do_kho'] ?? 'Trung bình');

        // Parse tags
        $tags = !empty($row['tags']) ? explode(',', $row['tags']) : null;
        if ($tags) {
            $tags = array_map('trim', $tags);
        }

        // Create question
        $question = Question::create([
            'subject_id' => $this->subjectId,
            'topic_id' => $topic?->id,
            'content' => $row['noi_dung_cau_hoi'],
            'type' => $type,
            'bloom_level' => $bloomLevel,
            'difficulty' => $difficulty,
            'points' => $row['diem'] ?? 1,
            'explanation' => $row['giai_thich'] ?? null,
            'tags' => $tags,
            'created_by' => Auth::id(),
            'in_question_bank' => true,
        ]);

        // Create answers for multiple choice questions
        if (in_array($type, ['multiple_choice', 'true_false'])) {
            $this->createAnswers($question, $row);
        }

        return $question;
    }

    protected function createAnswers($question, $row)
    {
        $answers = [];
        $correctAnswers = explode(',', strtoupper($row['dap_an_dung'] ?? 'A'));
        $correctAnswers = array_map('trim', $correctAnswers);

        // Create answers A, B, C, D
        $answerLetters = ['A' => 'dap_an_a', 'B' => 'dap_an_b', 'C' => 'dap_an_c', 'D' => 'dap_an_d'];
        $order = 1;

        foreach ($answerLetters as $letter => $column) {
            if (!empty($row[$column])) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $row[$column],
                    'is_correct' => in_array($letter, $correctAnswers),
                    'order' => $order++,
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.noi_dung_cau_hoi' => 'nullable|string',
            '*.diem' => 'nullable|numeric|min:0',
        ];
    }

    private function mapQuestionType($type)
    {
        $map = [
            'Trắc nghiệm' => 'multiple_choice',
            'Đúng/Sai' => 'true_false',
            'Tự luận' => 'essay',
            'Điền khuyết' => 'fill_blank',
        ];

        return $map[$type] ?? 'multiple_choice';
    }

    private function mapBloomLevel($level)
    {
        $map = [
            'Nhận biết' => 'remember',
            'Thông hiểu' => 'understand',
            'Vận dụng' => 'apply',
            'Vận dụng cao' => 'analyze',
        ];

        return $map[$level] ?? 'remember';
    }

    private function mapDifficulty($difficulty)
    {
        $map = [
            'Dễ' => 'easy',
            'Trung bình' => 'medium',
            'Khó' => 'hard',
        ];

        return $map[$difficulty] ?? 'medium';
    }
}
