<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

echo "=== CẬP NHẬT NỘI DUNG CÂU HỎI THẬT ===\n\n";

DB::beginTransaction();

try {
    $subjects = Subject::all();
    $updated = 0;
    
    foreach ($subjects as $subject) {
        echo "Đang cập nhật câu hỏi môn {$subject->name}...\n";
        
        $questions = Question::where('subject_id', $subject->id)
            ->where('content', 'LIKE', 'Câu hỏi %')
            ->get();
        
        foreach ($questions as $question) {
            $realContent = '';
            $realAnswers = [];
            
            // Generate real questions based on subject
            switch ($subject->name) {
                case 'Toán':
                    $mathQuestions = [
                        ['q' => 'Tính giá trị của biểu thức: 2x + 3 = 11. Giá trị của x là:', 'a' => ['x = 4', 'x = 3', 'x = 5', 'x = 7']],
                        ['q' => 'Phương trình bậc hai x² - 5x + 6 = 0 có nghiệm là:', 'a' => ['x = 2 hoặc x = 3', 'x = 1 hoặc x = 6', 'x = -2 hoặc x = -3', 'Vô nghiệm']],
                        ['q' => 'Diện tích hình vuông có cạnh 5cm là:', 'a' => ['25 cm²', '20 cm²', '30 cm²', '10 cm²']],
                        ['q' => 'Đạo hàm của hàm số y = x³ là:', 'a' => ['y\' = 3x²', 'y\' = x²', 'y\' = 3x', 'y\' = x³']],
                        ['q' => 'Tích phân của ∫x dx từ 0 đến 2 bằng:', 'a' => ['2', '4', '1', '3']],
                        ['q' => 'Sin²x + Cos²x bằng:', 'a' => ['1', '0', '2', 'x']],
                        ['q' => 'Giới hạn lim(x→0) sin(x)/x bằng:', 'a' => ['1', '0', '∞', 'Không tồn tại']],
                        ['q' => 'Công thức tính chu vi hình tròn bán kính r là:', 'a' => ['2πr', 'πr²', 'πr', '4πr']],
                    ];
                    $randomQ = $mathQuestions[array_rand($mathQuestions)];
                    $realContent = $randomQ['q'];
                    $realAnswers = $randomQ['a'];
                    break;
                    
                case 'Vật lý':
                    $physicsQuestions = [
                        ['q' => 'Gia tốc trọng trường trên Trái Đất có giá trị xấp xỉ:', 'a' => ['9.8 m/s²', '10 m/s²', '8 m/s²', '12 m/s²']],
                        ['q' => 'Định luật Ôm được phát biểu:', 'a' => ['I = U/R', 'U = I/R', 'R = I×U', 'P = U/I']],
                        ['q' => 'Vận tốc ánh sáng trong chân không là:', 'a' => ['3×10⁸ m/s', '3×10⁶ m/s', '3×10⁹ m/s', '3×10⁷ m/s']],
                        ['q' => 'Đơn vị đo công suất trong hệ SI là:', 'a' => ['Watt (W)', 'Joule (J)', 'Newton (N)', 'Volt (V)']],
                        ['q' => 'Lực hấp dẫn giữa hai vật tỉ lệ:', 'a' => ['Thuận với tích khối lượng, nghịch với bình phương khoảng cách', 'Thuận với tổng khối lượng', 'Nghịch với khối lượng', 'Thuận với khoảng cách']],
                        ['q' => 'Nhiệt độ sôi của nước ở áp suất tiêu chuẩn:', 'a' => ['100°C', '0°C', '50°C', '200°C']],
                        ['q' => 'Công thức tính động năng là:', 'a' => ['Wđ = 1/2 mv²', 'Wđ = mgh', 'Wđ = Fs', 'Wđ = mv']],
                        ['q' => 'Hiện tượng khúc xạ ánh sáng xảy ra khi:', 'a' => ['Ánh sáng truyền từ môi trường này sang môi trường khác', 'Ánh sáng phản xạ', 'Ánh sáng bị hấp thụ', 'Ánh sáng truyền thẳng']],
                    ];
                    $randomQ = $physicsQuestions[array_rand($physicsQuestions)];
                    $realContent = $randomQ['q'];
                    $realAnswers = $randomQ['a'];
                    break;
                    
                case 'Hóa học':
                    $chemQuestions = [
                        ['q' => 'Công thức hóa học của nước là:', 'a' => ['H₂O', 'H₂O₂', 'CO₂', 'NaCl']],
                        ['q' => 'Số Avogadro có giá trị:', 'a' => ['6.022 × 10²³', '6.022 × 10²²', '3.14 × 10²³', '1.6 × 10⁻¹⁹']],
                        ['q' => 'Kim loại kiềm mạnh nhất là:', 'a' => ['Cs (Xesi)', 'Na (Natri)', 'K (Kali)', 'Li (Liti)']],
                        ['q' => 'Phản ứng oxi hóa - khử là phản ứng:', 'a' => ['Có sự thay đổi số oxi hóa', 'Không có sự thay đổi số oxi hóa', 'Chỉ xảy ra ở nhiệt độ cao', 'Tỏa nhiệt']],
                        ['q' => 'pH của dung dịch trung tính là:', 'a' => ['7', '0', '14', '1']],
                        ['q' => 'Khí CO₂ được tạo thành từ:', 'a' => ['Carbon và Oxygen', 'Carbon và Nitrogen', 'Hydrogen và Oxygen', 'Nitrogen và Oxygen']],
                        ['q' => 'Axit mạnh nhất trong các axit sau:', 'a' => ['HCl', 'CH₃COOH', 'H₂CO₃', 'H₃PO₄']],
                        ['q' => 'Nguyên tố có ký hiệu hóa học Fe là:', 'a' => ['Sắt', 'Vàng', 'Bạc', 'Đồng']],
                    ];
                    $randomQ = $chemQuestions[array_rand($chemQuestions)];
                    $realContent = $randomQ['q'];
                    $realAnswers = $randomQ['a'];
                    break;
                    
                case 'Lập trình Web':
                    $webQuestions = [
                        ['q' => 'HTML là viết tắt của:', 'a' => ['HyperText Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language']],
                        ['q' => 'CSS dùng để làm gì?', 'a' => ['Định dạng giao diện trang web', 'Lập trình logic', 'Quản lý database', 'Tạo server']],
                        ['q' => 'Thẻ HTML để tạo liên kết là:', 'a' => ['<a>', '<link>', '<href>', '<url>']],
                        ['q' => 'JavaScript chạy ở đâu?', 'a' => ['Trình duyệt và server', 'Chỉ trình duyệt', 'Chỉ server', 'Database']],
                        ['q' => 'Laravel là framework của ngôn ngữ:', 'a' => ['PHP', 'JavaScript', 'Python', 'Java']],
                        ['q' => 'HTTP Status Code 404 có nghĩa là:', 'a' => ['Not Found', 'Server Error', 'Success', 'Forbidden']],
                        ['q' => 'Method HTTP để lấy dữ liệu là:', 'a' => ['GET', 'POST', 'PUT', 'DELETE']],
                        ['q' => 'SQL là ngôn ngữ để:', 'a' => ['Truy vấn cơ sở dữ liệu', 'Tạo giao diện', 'Xử lý logic', 'Tạo API']],
                    ];
                    $randomQ = $webQuestions[array_rand($webQuestions)];
                    $realContent = $randomQ['q'];
                    $realAnswers = $randomQ['a'];
                    break;
                    
                case 'Giai tich':
                    $calculusQuestions = [
                        ['q' => 'Đạo hàm của hàm số y = sin(x) là:', 'a' => ['cos(x)', '-cos(x)', 'sin(x)', '-sin(x)']],
                        ['q' => 'Tích phân của hàm hằng số c là:', 'a' => ['cx + C', 'c + C', 'x + C', 'C']],
                        ['q' => 'Giới hạn lim(x→∞) 1/x bằng:', 'a' => ['0', '∞', '1', 'Không tồn tại']],
                        ['q' => 'Quy tắc L\'Hospital áp dụng cho dạng:', 'a' => ['0/0 hoặc ∞/∞', 'Chỉ 0/0', 'Chỉ ∞/∞', 'Mọi dạng']],
                        ['q' => 'Chuỗi hội tụ khi:', 'a' => ['Tổng các số hạng tiến về một giá trị hữu hạn', 'Tổng tiến về vô cùng', 'Các số hạng bằng 0', 'Không có điều kiện']],
                        ['q' => 'Đạo hàm cấp hai của y = x³ là:', 'a' => ['6x', '3x²', 'x²', '3x']],
                        ['q' => 'Nguyên hàm của e^x là:', 'a' => ['e^x + C', 'xe^x + C', 'e^(x+1) + C', 'ln(x) + C']],
                        ['q' => 'Chuỗi Taylor khai triển hàm tại điểm:', 'a' => ['Một điểm cụ thể', 'Vô cùng', 'Gốc tọa độ', 'Mọi điểm']],
                    ];
                    $randomQ = $calculusQuestions[array_rand($calculusQuestions)];
                    $realContent = $randomQ['q'];
                    $realAnswers = $randomQ['a'];
                    break;
            }
            
            if ($realContent && $question->type === 'multiple_choice') {
                // Update question content
                $question->content = $realContent;
                $question->save();
                
                // Update answers
                $answers = $question->answers()->get();
                if ($answers->count() >= 4 && count($realAnswers) >= 4) {
                    foreach ($answers as $index => $answer) {
                        if (isset($realAnswers[$index])) {
                            $answer->content = $realAnswers[$index];
                            $answer->is_correct = ($index === 0) ? 1 : 0; // First answer is correct
                            $answer->save();
                        }
                    }
                }
                
                $updated++;
            }
        }
    }
    
    DB::commit();
    echo "\n✅ Đã cập nhật {$updated} câu hỏi với nội dung thật!\n";
    
} catch (\Exception $e) {
    DB::rollback();
    echo "\n❌ LỖI: " . $e->getMessage() . "\n";
}

echo "\n=== KIỂM TRA MẪU CÂU HỎI ===\n";
$samples = Question::where('type', 'multiple_choice')
    ->with('answers', 'subject')
    ->inRandomOrder()
    ->take(5)
    ->get();

foreach ($samples as $q) {
    echo "\nMôn: {$q->subject->name}\n";
    echo "Câu hỏi: {$q->content}\n";
    foreach ($q->answers as $a) {
        $mark = $a->is_correct ? ' ✓' : '';
        echo "  - {$a->content}{$mark}\n";
    }
}

echo "\n";
