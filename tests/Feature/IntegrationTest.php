<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\ExamSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $student;
    protected $subject;
    protected $exam;
    protected $chatRoom;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');
        
        $this->student = User::factory()->create();
        $this->student->assignRole('student');
        
        $this->subject = Subject::factory()->create([
            'teacher_id' => $this->teacher->id
        ]);
        
        $this->exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'created_by' => $this->teacher->id,
            'status' => 'active',
        ]);
        
        $this->chatRoom = ChatRoom::factory()->create([
            'name' => 'Class Discussion',
            'type' => 'group',
        ]);
        
        $this->chatRoom->users()->attach([$this->teacher->id, $this->student->id]);
    }

    /** @test */
    public function complete_exam_workflow_with_chat_support()
    {
        // 1. Student asks question in chat before exam
        $this->actingAs($this->student);
        
        $response = $this->postJson('/api/v1/chat/send', [
            'room_id' => $this->chatRoom->id,
            'message' => 'Thầy ơi, đề thi có khó không ạ?',
        ]);
        $response->assertStatus(200);
        
        // 2. Teacher responds in chat
        $this->actingAs($this->teacher);
        
        $response = $this->postJson('/api/v1/chat/send', [
            'room_id' => $this->chatRoom->id,
            'message' => 'Đề vừa phải thôi, chuẩn bị kỹ nhé!',
        ]);
        $response->assertStatus(200);
        
        // 3. Student takes exam
        $this->actingAs($this->student);
        
        $response = $this->get(route('student.exams.take', $this->exam->id));
        $response->assertStatus(200);
        
        // 4. Student submits exam
        $submissionData = [
            'answers' => [1 => 'A', 2 => 'B', 3 => 'C'],
            'time_spent' => 45,
        ];
        
        $response = $this->post(route('student.exams.submit', $this->exam->id), $submissionData);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('exam_submissions', [
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'status' => 'submitted',
        ]);
        
        // 5. Student asks about results in chat
        $response = $this->postJson('/api/v1/chat/send', [
            'room_id' => $this->chatRoom->id,
            'message' => 'Em vừa nộp bài xong ạ!',
        ]);
        $response->assertStatus(200);
        
        // 6. Teacher grades and responds
        $this->actingAs($this->teacher);
        
        $submission = ExamSubmission::where('exam_id', $this->exam->id)
            ->where('student_id', $this->student->id)
            ->first();
        
        $submission->update([
            'marks_obtained' => 85,
            'status' => 'graded',
        ]);
        
        $response = $this->postJson('/api/v1/chat/send', [
            'room_id' => $this->chatRoom->id,
            'message' => 'Em làm tốt lắm, được 85 điểm!',
        ]);
        $response->assertStatus(200);
        
        // Verify complete workflow
        $this->assertDatabaseHas('chat_messages', [
            'room_id' => $this->chatRoom->id,
            'user_id' => $this->student->id,
        ]);
        
        $this->assertDatabaseHas('exam_submissions', [
            'exam_id' => $this->exam->id,
            'student_id' => $this->student->id,
            'marks_obtained' => 85,
            'status' => 'graded',
        ]);
    }

    /** @test */
    public function teacher_creates_exam_and_notifies_via_chat()
    {
        $this->actingAs($this->teacher);
        
        // 1. Create new exam
        $examData = [
            'title' => 'Kiểm tra cuối kỳ',
            'subject_id' => $this->subject->id,
            'duration' => 90,
            'total_marks' => 100,
            'start_time' => now()->addDays(3),
            'end_time' => now()->addDays(10),
        ];
        
        $response = $this->post(route('teacher.exams.store'), $examData);
        $response->assertRedirect();
        
        $exam = Exam::where('title', 'Kiểm tra cuối kỳ')->first();
        $this->assertNotNull($exam);
        
        // 2. Announce in chat
        $response = $this->postJson('/api/v1/chat/send', [
            'room_id' => $this->chatRoom->id,
            'message' => "Thông báo: Kiểm tra cuối kỳ sẽ diễn ra từ ngày " . $exam->start_time->format('d/m/Y'),
        ]);
        $response->assertStatus(200);
        
        // 3. Student receives notification
        $this->actingAs($this->student);
        
        $response = $this->getJson("/api/v1/chat/rooms/{$this->chatRoom->id}/messages");
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => "Thông báo: Kiểm tra cuối kỳ sẽ diễn ra từ ngày " . $exam->start_time->format('d/m/Y')]);
    }

    /** @test */
    public function ai_assistant_helps_student_with_exam_questions()
    {
        $this->actingAs($this->student);
        
        // Student asks AI for help
        $response = $this->postJson('/api/v1/chat/send', [
            'room_id' => $this->chatRoom->id,
            'message' => '@ai Giải thích công thức tính diện tích hình tròn?',
        ]);
        
        $response->assertStatus(200);
        
        // Check AI response exists
        $this->assertDatabaseHas('chat_messages', [
            'room_id' => $this->chatRoom->id,
            'is_ai' => true,
        ]);
        
        $aiMessage = ChatMessage::where('room_id', $this->chatRoom->id)
            ->where('is_ai', true)
            ->first();
        
        $this->assertNotNull($aiMessage);
        $this->assertStringContainsString('diện tích', strtolower($aiMessage->message));
    }

    /** @test */
    public function multiple_students_submit_exam_and_discuss_in_chat()
    {
        // Create more students
        $student2 = User::factory()->create();
        $student2->assignRole('student');
        $this->chatRoom->users()->attach($student2->id);
        
        $student3 = User::factory()->create();
        $student3->assignRole('student');
        $this->chatRoom->users()->attach($student3->id);
        
        // All students submit exam
        foreach ([$this->student, $student2, $student3] as $index => $student) {
            $this->actingAs($student);
            
            $response = $this->post(route('student.exams.submit', $this->exam->id), [
                'answers' => [1 => 'A', 2 => 'B'],
                'time_spent' => 30 + $index * 5,
            ]);
            
            $response->assertRedirect();
            
            // Student discusses in chat
            $this->postJson('/api/v1/chat/send', [
                'room_id' => $this->chatRoom->id,
                'message' => "Mình vừa nộp bài rồi! Các bạn thấy đề thế nào?",
            ]);
        }
        
        // Verify all submissions
        $this->assertEquals(3, ExamSubmission::where('exam_id', $this->exam->id)->count());
        
        // Verify chat activity
        $this->assertEquals(3, ChatMessage::where('room_id', $this->chatRoom->id)
            ->where('message', 'like', '%nộp bài%')
            ->count());
    }

    /** @test */
    public function exam_statistics_match_chat_activity()
    {
        // Create submissions
        ExamSubmission::factory()->count(5)->create([
            'exam_id' => $this->exam->id,
            'status' => 'graded',
        ]);
        
        // Create chat messages about exam
        $this->actingAs($this->teacher);
        
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/chat/send', [
                'room_id' => $this->chatRoom->id,
                'message' => "Đã chấm xong bài của học sinh thứ " . ($i + 1),
            ]);
        }
        
        // Check statistics
        $submissionCount = ExamSubmission::where('exam_id', $this->exam->id)->count();
        $chatMessagesAboutGrading = ChatMessage::where('room_id', $this->chatRoom->id)
            ->where('message', 'like', '%chấm xong%')
            ->count();
        
        $this->assertEquals($submissionCount, $chatMessagesAboutGrading);
    }
}
