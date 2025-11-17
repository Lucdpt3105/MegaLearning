<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Provide sample data
        return [
            [
                'Chương 1',
                'Số nguyên tố là số tự nhiên lớn hơn 1 chỉ chia hết cho 1 và chính nó?',
                'Trắc nghiệm',
                'Nhận biết',
                'Dễ',
                1,
                'Đúng',
                'Sai',
                '',
                '',
                'A',
                'Theo định nghĩa số nguyên tố',
                'số nguyên tố, toán học',
            ],
            [
                'Chương 1',
                '2 + 2 = ?',
                'Trắc nghiệm',
                'Nhận biết',
                'Dễ',
                1,
                '3',
                '4',
                '5',
                '6',
                'B',
                'Phép cộng cơ bản',
                'cộng, toán học',
            ],
            [
                'Chương 2',
                'Giải thích khái niệm về hàm số bậc nhất',
                'Tự luận',
                'Thông hiểu',
                'Trung bình',
                5,
                '',
                '',
                '',
                '',
                '',
                'Hàm số bậc nhất có dạng y = ax + b với a ≠ 0',
                'hàm số, đại số',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'chuong_bai',
            'noi_dung_cau_hoi',
            'loai_cau_hoi',
            'muc_do_bloom',
            'do_kho',
            'diem',
            'dap_an_a',
            'dap_an_b',
            'dap_an_c',
            'dap_an_d',
            'dap_an_dung',
            'giai_thich',
            'tags',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Add instructions at the top
        $sheet->insertNewRowBefore(1, 6);
        $sheet->setCellValue('A1', 'HƯỚNG DẪN IMPORT CÂU HỎI');
        $sheet->setCellValue('A2', '1. Loại câu hỏi (loai_cau_hoi): Trắc nghiệm / Đúng/Sai / Tự luận / Điền khuyết');
        $sheet->setCellValue('A3', '2. Mức độ Bloom (muc_do_bloom): Nhận biết / Thông hiểu / Vận dụng / Vận dụng cao');
        $sheet->setCellValue('A4', '3. Độ khó (do_kho): Dễ / Trung bình / Khó');
        $sheet->setCellValue('A5', '4. Đáp án đúng (dap_an_dung): Nhập A, B, C, hoặc D. Nhiều đáp án: A,B');
        $sheet->setCellValue('A6', '5. KHÔNG ĐƯỢC XÓA DÒNG TIÊU ĐỀ (dòng 7)');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '4F46E5']]],
            2 => ['font' => ['size' => 10, 'color' => ['rgb' => '666666']]],
            3 => ['font' => ['size' => 10, 'color' => ['rgb' => '666666']]],
            4 => ['font' => ['size' => 10, 'color' => ['rgb' => '666666']]],
            5 => ['font' => ['size' => 10, 'color' => ['rgb' => '666666']]],
            6 => ['font' => ['size' => 10, 'color' => ['rgb' => 'FF0000'], 'bold' => true]],
            7 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Chương/Bài
            'B' => 50,  // Nội dung
            'C' => 15,  // Loại
            'D' => 15,  // Bloom
            'E' => 12,  // Độ khó
            'F' => 8,   // Điểm
            'G' => 30,  // Đáp án A
            'H' => 30,  // Đáp án B
            'I' => 30,  // Đáp án C
            'J' => 30,  // Đáp án D
            'K' => 12,  // Đáp án đúng
            'L' => 40,  // Giải thích
            'M' => 20,  // Tags
        ];
    }
}
