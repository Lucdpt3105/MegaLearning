<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@megalearning.com')->first();
        $admin = User::where('email', 'admin@megalearning.com')->first();
        
        if (!$teacher || !$admin) {
            $this->command->error('Teacher or Admin user not found. Please run UserSeeder first.');
            return;
        }

        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            $this->command->error('No subjects found. Please run SubjectSeeder first.');
            return;
        }

        // Sample documents for each subject
        $documentTemplates = [
            // Math Documents
            [
                'subject_name' => 'Toán học',
                'documents' => [
                    [
                        'title' => 'Giáo trình Đại số tuyến tính',
                        'description' => 'Tài liệu chi tiết về ma trận, định thức, hệ phương trình tuyến tính',
                        'file_type' => 'pdf',
                        'file_size' => 2548000,
                        'original_name' => 'giao-trinh-dai-so-tuyen-tinh.pdf',
                    ],
                    [
                        'title' => 'Bài tập Giải tích 1',
                        'description' => '100+ bài tập về đạo hàm, tích phân, giới hạn có lời giải chi tiết',
                        'file_type' => 'pdf',
                        'file_size' => 1825000,
                        'original_name' => 'bai-tap-giai-tich-1.pdf',
                    ],
                    [
                        'title' => 'Chuyên đề Hình học không gian',
                        'description' => 'Các dạng bài tập và phương pháp giải hình học không gian',
                        'file_type' => 'pdf',
                        'file_size' => 3120000,
                        'original_name' => 'hinh-hoc-khong-gian.pdf',
                    ],
                    [
                        'title' => 'Slide bài giảng Toán rời rạc',
                        'description' => 'Bài giảng PowerPoint về tổ hợp, đồ thị, logic',
                        'file_type' => 'pptx',
                        'file_size' => 4560000,
                        'original_name' => 'toan-roi-rac.pptx',
                    ],
                ],
            ],
            // Physics Documents
            [
                'subject_name' => 'Vật lý',
                'documents' => [
                    [
                        'title' => 'Cơ học chất điểm',
                        'description' => 'Lý thuyết và bài tập về chuyển động thẳng, chuyển động tròn',
                        'file_type' => 'pdf',
                        'file_size' => 2850000,
                        'original_name' => 'co-hoc-chat-diem.pdf',
                    ],
                    [
                        'title' => 'Điện từ học cơ bản',
                        'description' => 'Định luật Coulomb, định luật Ohm, mạch điện',
                        'file_type' => 'pdf',
                        'file_size' => 3240000,
                        'original_name' => 'dien-tu-hoc.pdf',
                    ],
                    [
                        'title' => 'Thí nghiệm Vật lý đại cương',
                        'description' => 'Hướng dẫn thực hành 15 thí nghiệm vật lý',
                        'file_type' => 'docx',
                        'file_size' => 1950000,
                        'original_name' => 'thi-nghiem-vat-ly.docx',
                    ],
                    [
                        'title' => 'Video bài giảng Quang học',
                        'description' => 'Link video bài giảng về giao thoa, nhiễu xạ ánh sáng',
                        'file_type' => 'txt',
                        'file_size' => 1500,
                        'original_name' => 'quang-hoc-video-links.txt',
                    ],
                ],
            ],
            // Chemistry Documents
            [
                'subject_name' => 'Hóa học',
                'documents' => [
                    [
                        'title' => 'Bảng tuần hoàn các nguyên tố hóa học',
                        'description' => 'Bảng tuần hoàn đầy đủ với thông tin chi tiết về các nguyên tố',
                        'file_type' => 'pdf',
                        'file_size' => 1250000,
                        'original_name' => 'bang-tuan-hoan.pdf',
                    ],
                    [
                        'title' => 'Hóa học hữu cơ',
                        'description' => 'Các phản ứng đặc trưng của hidrocacbon, ancol, axit',
                        'file_type' => 'pdf',
                        'file_size' => 2980000,
                        'original_name' => 'hoa-huu-co.pdf',
                    ],
                    [
                        'title' => 'Bài tập cân bằng phương trình hóa học',
                        'description' => '50 phương trình hóa học từ cơ bản đến nâng cao',
                        'file_type' => 'pdf',
                        'file_size' => 985000,
                        'original_name' => 'can-bang-phuong-trinh.pdf',
                    ],
                ],
            ],
            // Web Programming Documents
            [
                'subject_name' => 'Lập trình Web',
                'documents' => [
                    [
                        'title' => 'Laravel Documentation tiếng Việt',
                        'description' => 'Tài liệu Laravel 10 đầy đủ được dịch sang tiếng Việt',
                        'file_type' => 'pdf',
                        'file_size' => 5640000,
                        'original_name' => 'laravel-10-vietnamese.pdf',
                    ],
                    [
                        'title' => 'Tailwind CSS Cheatsheet',
                        'description' => 'Bảng tra cứu nhanh các utility classes của Tailwind CSS',
                        'file_type' => 'pdf',
                        'file_size' => 850000,
                        'original_name' => 'tailwind-cheatsheet.pdf',
                    ],
                    [
                        'title' => 'Vue.js 3 Composition API',
                        'description' => 'Hướng dẫn sử dụng Composition API trong Vue 3',
                        'file_type' => 'pdf',
                        'file_size' => 1750000,
                        'original_name' => 'vue3-composition-api.pdf',
                    ],
                    [
                        'title' => 'Source code dự án E-commerce',
                        'description' => 'Source code mẫu website thương mại điện tử với Laravel + Vue',
                        'file_type' => 'zip',
                        'file_size' => 12500000,
                        'original_name' => 'ecommerce-laravel-vue.zip',
                    ],
                    [
                        'title' => 'REST API Best Practices',
                        'description' => 'Các nguyên tắc thiết kế RESTful API chuẩn mực',
                        'file_type' => 'pdf',
                        'file_size' => 1280000,
                        'original_name' => 'rest-api-best-practices.pdf',
                    ],
                ],
            ],
            // Database Documents
            [
                'subject_name' => 'Cơ sở dữ liệu',
                'documents' => [
                    [
                        'title' => 'MySQL Fundamentals',
                        'description' => 'Giáo trình MySQL từ cơ bản đến nâng cao',
                        'file_type' => 'pdf',
                        'file_size' => 3450000,
                        'original_name' => 'mysql-fundamentals.pdf',
                    ],
                    [
                        'title' => 'Thiết kế CSDL với ERD',
                        'description' => 'Hướng dẫn vẽ sơ đồ ERD và chuẩn hóa dữ liệu',
                        'file_type' => 'pdf',
                        'file_size' => 2150000,
                        'original_name' => 'thiet-ke-csdl-erd.pdf',
                    ],
                    [
                        'title' => 'SQL Query Optimization',
                        'description' => 'Các kỹ thuật tối ưu hóa câu truy vấn SQL',
                        'file_type' => 'pdf',
                        'file_size' => 1890000,
                        'original_name' => 'sql-optimization.pdf',
                    ],
                    [
                        'title' => 'MongoDB cho người mới bắt đầu',
                        'description' => 'Giới thiệu về NoSQL và cách sử dụng MongoDB',
                        'file_type' => 'pdf',
                        'file_size' => 2740000,
                        'original_name' => 'mongodb-beginner.pdf',
                    ],
                    [
                        'title' => 'Database Backup & Recovery',
                        'description' => 'Chiến lược sao lưu và phục hồi cơ sở dữ liệu',
                        'file_type' => 'docx',
                        'file_size' => 1650000,
                        'original_name' => 'database-backup-recovery.docx',
                    ],
                ],
            ],
        ];

        // Create fake file paths (in production, these would be real uploads)
        $documentsCreated = 0;
        
        foreach ($documentTemplates as $template) {
            $subject = $subjects->firstWhere('name', $template['subject_name']);
            
            if (!$subject) {
                continue;
            }

            foreach ($template['documents'] as $docData) {
                // Create fake file path
                $fakePath = 'documents/' . $subject->id . '/' . $docData['original_name'];
                
                Document::create([
                    'subject_id' => $subject->id,
                    'uploaded_by' => $teacher->id,
                    'title' => $docData['title'],
                    'description' => $docData['description'],
                    'file_path' => $fakePath,
                    'file_type' => $docData['file_type'],
                    'file_size' => $docData['file_size'],
                    'file_name' => $docData['original_name'], // Đổi thành file_name
                    'approval_status' => 'approved',
                    'approved_by' => $admin->id,
                    'approved_at' => now(),
                    'download_count' => rand(0, 150),
                ]);
                
                $documentsCreated++;
            }
        }

        $this->command->info("✅ Created {$documentsCreated} sample documents!");
        $this->command->line('   Note: These are fake file paths. In production, upload real files.');
    }
}
