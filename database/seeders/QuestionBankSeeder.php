<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user as teacher (adjust based on your user structure)
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

        $difficulties = ['easy', 'medium', 'hard'];
        $bloomLevels = ['remember', 'understand', 'apply', 'analyze'];
        
        // Math questions
        $mathQuestions = [
            ['content' => 'Giải phương trình: 2x + 5 = 13', 'answers' => ['x = 4', 'x = 5', 'x = 3', 'x = 6'], 'correct' => 0],
            ['content' => 'Tính giá trị của biểu thức: 3² + 4²', 'answers' => ['25', '49', '12', '24'], 'correct' => 0],
            ['content' => 'Đạo hàm của hàm số y = x³ là:', 'answers' => ['3x²', '2x²', 'x²', '3x'], 'correct' => 0],
            ['content' => 'Sin²x + Cos²x bằng:', 'answers' => ['1', '0', '2', '-1'], 'correct' => 0],
            ['content' => 'Tích phân của ∫x dx từ 0 đến 1 bằng:', 'answers' => ['1/2', '1', '2', '1/3'], 'correct' => 0],
            ['content' => 'Căn bậc hai của 144 là:', 'answers' => ['12', '14', '10', '11'], 'correct' => 0],
            ['content' => 'Công thức tính diện tích hình tròn:', 'answers' => ['πr²', '2πr', 'πd', 'r²'], 'correct' => 0],
            ['content' => 'Log₁₀(100) bằng:', 'answers' => ['2', '10', '100', '1'], 'correct' => 0],
            ['content' => 'Số nghiệm của phương trình x² - 4 = 0:', 'answers' => ['2', '1', '3', '0'], 'correct' => 0],
            ['content' => 'Giá trị của π gần đúng là:', 'answers' => ['3.14', '3.41', '2.14', '4.13'], 'correct' => 0],
            ['content' => 'Tổng các góc trong tam giác bằng:', 'answers' => ['180°', '90°', '360°', '270°'], 'correct' => 0],
            ['content' => 'Công thức tính chu vi hình chữ nhật:', 'answers' => ['2(a+b)', 'a+b', 'ab', '2ab'], 'correct' => 0],
            ['content' => 'Giá trị tuyệt đối của -5:', 'answers' => ['5', '-5', '0', '10'], 'correct' => 0],
            ['content' => 'Số nguyên tố nhỏ nhất là:', 'answers' => ['2', '1', '3', '0'], 'correct' => 0],
            ['content' => 'Định lý Pythagore: a² + b² =', 'answers' => ['c²', 'c', '2c', '√c'], 'correct' => 0],
            ['content' => 'Đạo hàm của hàm số y = sin(x):', 'answers' => ['cos(x)', '-cos(x)', 'sin(x)', '-sin(x)'], 'correct' => 0],
            ['content' => 'Giới hạn lim(x→0) sin(x)/x bằng:', 'answers' => ['1', '0', '∞', 'không tồn tại'], 'correct' => 0],
            ['content' => 'Tổ hợp C(5,2) bằng:', 'answers' => ['10', '20', '5', '25'], 'correct' => 0],
            ['content' => 'Phương trình đường thẳng đi qua (0,0) với hệ số góc 2:', 'answers' => ['y = 2x', 'y = x + 2', 'y = 2', 'x = 2y'], 'correct' => 0],
            ['content' => 'Ma trận đơn vị cấp 2 là:', 'answers' => ['[[1,0],[0,1]]', '[[0,1],[1,0]]', '[[1,1],[1,1]]', '[[2,0],[0,2]]'], 'correct' => 0],
        ];

        // Physics questions
        $physicsQuestions = [
            ['content' => 'Công thức tính vận tốc:', 'answers' => ['v = s/t', 'v = s×t', 'v = t/s', 'v = s+t'], 'correct' => 0],
            ['content' => 'Định luật Newton thứ 2: F =', 'answers' => ['ma', 'm/a', 'a/m', 'm+a'], 'correct' => 0],
            ['content' => 'Vận tốc ánh sáng trong chân không:', 'answers' => ['3×10⁸ m/s', '3×10⁶ m/s', '3×10⁷ m/s', '3×10⁹ m/s'], 'correct' => 0],
            ['content' => 'Công thức tính động năng:', 'answers' => ['Wd = ½mv²', 'Wd = mv', 'Wd = mv²', 'Wd = 2mv²'], 'correct' => 0],
            ['content' => 'Đơn vị đo công suất:', 'answers' => ['Watt', 'Joule', 'Newton', 'Pascal'], 'correct' => 0],
            ['content' => 'Điện trở tương đương mắc nối tiếp R₁ và R₂:', 'answers' => ['R = R₁ + R₂', 'R = R₁ × R₂', '1/R = 1/R₁ + 1/R₂', 'R = R₁ - R₂'], 'correct' => 0],
            ['content' => 'Định luật Ohm: I =', 'answers' => ['U/R', 'U×R', 'R/U', 'U+R'], 'correct' => 0],
            ['content' => 'Gia tốc trọng trường g ≈', 'answers' => ['9.8 m/s²', '10 m/s', '9.8 m/s', '10 m/s²'], 'correct' => 0],
            ['content' => 'Công thức tính áp suất:', 'answers' => ['P = F/S', 'P = F×S', 'P = S/F', 'P = F+S'], 'correct' => 0],
            ['content' => 'Nhiệt độ sôi của nước ở áp suất tiêu chuẩn:', 'answers' => ['100°C', '0°C', '50°C', '200°C'], 'correct' => 0],
            ['content' => 'Công thức tính thế năng trọng trường:', 'answers' => ['Wt = mgh', 'Wt = mh', 'Wt = gh', 'Wt = mg'], 'correct' => 0],
            ['content' => 'Tần số và chu kỳ có mối quan hệ:', 'answers' => ['f = 1/T', 'f = T', 'f = 2T', 'f = T²'], 'correct' => 0],
            ['content' => 'Định luật bảo toàn năng lượng:', 'answers' => ['E = const', 'E = 0', 'E tăng', 'E giảm'], 'correct' => 0],
            ['content' => 'Lực hấp dẫn giữa 2 vật: F =', 'answers' => ['Gm₁m₂/r²', 'Gm₁m₂/r', 'Gm₁m₂r²', 'Gm₁+m₂/r²'], 'correct' => 0],
            ['content' => 'Công thức Einstein: E =', 'answers' => ['mc²', 'mc', 'm²c', 'mc²/2'], 'correct' => 0],
            ['content' => 'Điện tích electron:', 'answers' => ['-1.6×10⁻¹⁹ C', '1.6×10⁻¹⁹ C', '-1.6×10⁻¹⁸ C', '1.6×10⁻¹⁸ C'], 'correct' => 0],
            ['content' => 'Công thức tính công cơ học:', 'answers' => ['A = F.s', 'A = F/s', 'A = F+s', 'A = F-s'], 'correct' => 0],
            ['content' => 'Độ lớn lực ma sát: Fms =', 'answers' => ['μN', 'μ/N', 'N/μ', 'μ+N'], 'correct' => 0],
            ['content' => 'Định luật Boyle (nhiệt độ không đổi):', 'answers' => ['PV = const', 'P/V = const', 'P+V = const', 'P-V = const'], 'correct' => 0],
            ['content' => 'Momen lực: M =', 'answers' => ['F×d', 'F/d', 'F+d', 'F-d'], 'correct' => 0],
        ];

        // Chemistry questions
        $chemistryQuestions = [
            ['content' => 'Công thức hóa học của nước:', 'answers' => ['H₂O', 'H₂O₂', 'HO', 'H₃O'], 'correct' => 0],
            ['content' => 'Số Avogadro:', 'answers' => ['6.02×10²³', '6.02×10²²', '6.02×10²⁴', '6.02×10²¹'], 'correct' => 0],
            ['content' => 'Kim loại kiềm mạnh nhất:', 'answers' => ['Cs (Cesium)', 'Na (Sodium)', 'K (Potassium)', 'Li (Lithium)'], 'correct' => 0],
            ['content' => 'pH của dung dịch trung tính:', 'answers' => ['7', '0', '14', '1'], 'correct' => 0],
            ['content' => 'Khí nhẹ nhất:', 'answers' => ['H₂', 'He', 'O₂', 'N₂'], 'correct' => 0],
            ['content' => 'Công thức phân tử glucozo:', 'answers' => ['C₆H₁₂O₆', 'C₆H₁₀O₆', 'C₅H₁₂O₆', 'C₆H₁₂O₅'], 'correct' => 0],
            ['content' => 'Nguyên tố có số hiệu nguyên tử lớn nhất tự nhiên:', 'answers' => ['Uranium (92)', 'Plutonium (94)', 'Radium (88)', 'Thorium (90)'], 'correct' => 0],
            ['content' => 'Công thức muối ăn:', 'answers' => ['NaCl', 'KCl', 'CaCl₂', 'MgCl₂'], 'correct' => 0],
            ['content' => 'Khí quyển chủ yếu chứa khí gì:', 'answers' => ['N₂ (78%)', 'O₂ (78%)', 'CO₂ (78%)', 'Ar (78%)'], 'correct' => 0],
            ['content' => 'Kim loại dẫn điện tốt nhất:', 'answers' => ['Ag (Bạc)', 'Cu (Đồng)', 'Au (Vàng)', 'Al (Nhôm)'], 'correct' => 0],
            ['content' => 'Công thức axit sunfuric:', 'answers' => ['H₂SO₄', 'H₂SO₃', 'HSO₄', 'H₃SO₄'], 'correct' => 0],
            ['content' => 'Electron ở lớp ngoài cùng của khí hiếm:', 'answers' => ['8 (trừ He)', '2', '6', '10'], 'correct' => 0],
            ['content' => 'Phản ứng tỏa nhiệt:', 'answers' => ['ΔH < 0', 'ΔH > 0', 'ΔH = 0', 'ΔH = ∞'], 'correct' => 0],
            ['content' => 'Công thức canxi cacbonat:', 'answers' => ['CaCO₃', 'Ca(CO₃)₂', 'CaCO₂', 'Ca₂CO₃'], 'correct' => 0],
            ['content' => 'Kim loại có nhiệt độ nóng chảy thấp nhất:', 'answers' => ['Hg (Thủy ngân)', 'Ga (Gali)', 'Cs (Cesium)', 'Rb (Rubidi)'], 'correct' => 0],
            ['content' => 'Oxi hóa - khử: chất nhường electron là:', 'answers' => ['Chất khử', 'Chất oxi hóa', 'Chất trung gian', 'Xúc tác'], 'correct' => 0],
            ['content' => 'Công thức ammoniac:', 'answers' => ['NH₃', 'NH₄', 'N₂H₄', 'NH₂'], 'correct' => 0],
            ['content' => 'Độ âm điện lớn nhất:', 'answers' => ['F (Flo)', 'O (Oxi)', 'Cl (Clo)', 'N (Nitơ)'], 'correct' => 0],
            ['content' => 'Công thức methan:', 'answers' => ['CH₄', 'C₂H₄', 'C₂H₆', 'CH₃'], 'correct' => 0],
            ['content' => 'Đơn vị mol:', 'answers' => ['mol', 'g', 'L', 'atm'], 'correct' => 0],
        ];

        // Biology questions  
        $biologyQuestions = [
            ['content' => 'Tế bào được phát hiện bởi:', 'answers' => ['Robert Hooke', 'Darwin', 'Mendel', 'Watson'], 'correct' => 0],
            ['content' => 'ADN viết tắt của:', 'answers' => ['Deoxyribonucleic Acid', 'Ribonucleic Acid', 'Amino Acid', 'Acetic Acid'], 'correct' => 0],
            ['content' => 'Quang hợp xảy ra ở:', 'answers' => ['Lục lạp', 'Ti thể', 'Nhân', 'Ribosome'], 'correct' => 0],
            ['content' => 'Bào quan sản xuất ATP:', 'answers' => ['Ti thể', 'Lục lạp', 'Nhân', 'Ribosome'], 'correct' => 0],
            ['content' => 'Số nhiễm sắc thể ở người:', 'answers' => ['46', '23', '48', '24'], 'correct' => 0],
            ['content' => 'Đơn vị di truyền cơ bản:', 'answers' => ['Gen', 'Nhiễm sắc thể', 'ADN', 'Tế bào'], 'correct' => 0],
            ['content' => 'Hô hấp tế bào xảy ra ở:', 'answers' => ['Ti thể', 'Lục lạp', 'Nhân', 'Bộ máy Golgi'], 'correct' => 0],
            ['content' => 'Enzyme tiêu hóa protein trong dạ dày:', 'answers' => ['Pepsin', 'Amylase', 'Lipase', 'Trypsin'], 'correct' => 0],
            ['content' => 'Máu đỏ do:', 'answers' => ['Hemoglobin', 'Bạch cầu', 'Tiểu cầu', 'Huyết tương'], 'correct' => 0],
            ['content' => 'Cơ quan quang hợp chính của cây:', 'answers' => ['Lá', 'Rễ', 'Thân', 'Hoa'], 'correct' => 0],
            ['content' => 'Nhóm máu phổ biến nhất:', 'answers' => ['O', 'A', 'B', 'AB'], 'correct' => 0],
            ['content' => 'Chu trình tim gồm:', 'answers' => ['2 tâm nhĩ, 2 tâm thất', '1 tâm nhĩ, 1 tâm thất', '3 tâm nhĩ, 3 tâm thất', '4 tâm nhĩ'], 'correct' => 0],
            ['content' => 'Vi khuẩn thuộc giới:', 'answers' => ['Monera', 'Protista', 'Fungi', 'Plantae'], 'correct' => 0],
            ['content' => 'Quá trình nguyên phân tạo ra:', 'answers' => ['2 tế bào con giống nhau', '4 tế bào con', '2 tế bào khác nhau', '1 tế bào con'], 'correct' => 0],
            ['content' => 'Hormone điều hòa đường huyết:', 'answers' => ['Insulin', 'Adrenaline', 'Testosterone', 'Estrogen'], 'correct' => 0],
            ['content' => 'Thực vật C4 có ưu điểm:', 'answers' => ['Quang hợp hiệu quả hơn', 'Chịu hạn kém', 'Phát triển chậm', 'Cần nhiều nước'], 'correct' => 0],
            ['content' => 'Màng tế bào chủ yếu là:', 'answers' => ['Lipid kép', 'Protein', 'Carbohydrate', 'ADN'], 'correct' => 0],
            ['content' => 'Đột biến gen là:', 'answers' => ['Thay đổi ADN', 'Thay đổi nhiễm sắc thể', 'Thay đổi tế bào', 'Thay đổi cơ quan'], 'correct' => 0],
            ['content' => 'Cơ quan bài tiết chính:', 'answers' => ['Thận', 'Gan', 'Phổi', 'Da'], 'correct' => 0],
            ['content' => 'Hệ tuần hoàn kép ở:', 'answers' => ['Động vật có vú', 'Cá', 'Bò sát', 'Lưỡng cư'], 'correct' => 0],
        ];

        $allQuestions = array_merge($mathQuestions, $physicsQuestions, $chemistryQuestions, $biologyQuestions);
        
        $this->command->info('Creating 80 questions in question bank...');
        
        foreach ($allQuestions as $index => $questionData) {
            // Randomly assign to a subject
            $subject = $subjects->random();
            $topic = Topic::where('subject_id', $subject->id)->inRandomOrder()->first();
            
            $question = Question::create([
                'content' => $questionData['content'],
                'type' => 'multiple_choice',
                'subject_id' => $subject->id,
                'topic_id' => $topic?->id,
                'created_by' => $teacher->id,
                'difficulty' => $difficulties[array_rand($difficulties)],
                'bloom_level' => $bloomLevels[array_rand($bloomLevels)],
                'in_question_bank' => true,
                'points' => rand(1, 3),
                'explanation' => 'Đây là câu hỏi ' . ($index + 1) . ' trong ngân hàng câu hỏi.',
            ]);

            // Create answers
            foreach ($questionData['answers'] as $answerIndex => $answerText) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $answerText,
                    'is_correct' => $answerIndex === $questionData['correct'],
                    'order' => $answerIndex + 1,
                ]);
            }

            if (($index + 1) % 20 === 0) {
                $this->command->info('Created ' . ($index + 1) . ' questions...');
            }
        }

        $this->command->info('Successfully created 80 questions in question bank!');
    }
}
