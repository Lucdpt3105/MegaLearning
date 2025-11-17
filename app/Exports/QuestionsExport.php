<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $subjectId;

    public function __construct($subjectId)
    {
        $this->subjectId = $subjectId;
    }

    public function collection()
    {
        return Question::with(['topic', 'answers'])
            ->where('subject_id', $this->subjectId)
            ->where('in_question_bank', true)
            ->orderBy('topic_id')
            ->orderBy('bloom_level')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Chương/Bài',
            'Nội dung câu hỏi',
            'Loại câu hỏi',
            'Mức độ Bloom',
            'Độ khó',
            'Điểm',
            'Đáp án A',
            'Đáp án B',
            'Đáp án C',
            'Đáp án D',
            'Đáp án đúng',
            'Giải thích',
            'Tags',
            'Số lần sử dụng',
        ];
    }

    public function map($question): array
    {
        $answers = $question->answers->sortBy('order');
        
        // Get answer contents
        $answerA = $answers->get(0)?->content ?? '';
        $answerB = $answers->get(1)?->content ?? '';
        $answerC = $answers->get(2)?->content ?? '';
        $answerD = $answers->get(3)?->content ?? '';
        
        // Get correct answers (for multiple choice)
        $correctAnswers = [];
        foreach ($answers as $index => $answer) {
            if ($answer->is_correct) {
                $correctAnswers[] = chr(65 + $index); // A, B, C, D
            }
        }
        
        return [
            $question->id,
            $question->topic?->name ?? '',
            $question->content,
            $this->getQuestionTypeLabel($question->type),
            $this->getBloomLevelLabel($question->bloom_level),
            $this->getDifficultyLabel($question->difficulty),
            $question->points,
            $answerA,
            $answerB,
            $answerC,
            $answerD,
            implode(', ', $correctAnswers),
            $question->explanation ?? '',
            is_array($question->tags) ? implode(', ', $question->tags) : '',
            $question->usage_count,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    private function getQuestionTypeLabel($type)
    {
        return match($type) {
            'multiple_choice' => 'Trắc nghiệm',
            'true_false' => 'Đúng/Sai',
            'essay' => 'Tự luận',
            'fill_blank' => 'Điền khuyết',
            default => $type,
        };
    }

    private function getBloomLevelLabel($level)
    {
        return match($level) {
            'remember' => 'Nhận biết',
            'understand' => 'Thông hiểu',
            'apply' => 'Vận dụng',
            'analyze' => 'Vận dụng cao',
            default => $level,
        };
    }

    private function getDifficultyLabel($difficulty)
    {
        return match($difficulty) {
            'easy' => 'Dễ',
            'medium' => 'Trung bình',
            'hard' => 'Khó',
            default => $difficulty,
        };
    }
}
