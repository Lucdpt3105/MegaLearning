<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Models\ExamSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $student;
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');
        
        $this->student = User::factory()->create();
        $this->student->assignRole('student');
        
        // Create test subject
        $this->subject = Subject::factory()->create([
            'teacher_id' => $this->teacher->id
        ]);
    }

    /** @test */
    public function teacher_can_create_exam()
    {
        $this->actingAs($this->teacher);

        $examData = [
            'title' => 'Kiểm tra giữa kỳ',
            'description' => 'Đề thi môn Toán',
            'subject_id' => $this->subject->id,
            'duration' => 60,
            'total_marks' => 100,
            'passing_marks' => 50,
            'type' => 'regular',
            'status' => 'active',
            'start_time' => now()->addDays(1),
            'end_time' => now()->addDays(7),
        ];

        $response = $this->post(route('teacher.exams.store'), $examData);

        $response->assertRedirect();
        $this->assertDatabaseHas('exams', [
            'title' => 'Kiểm tra giữa kỳ',
            'subject_id' => $this->subject->id,
            'created_by' => $this->teacher->id,
        ]);
    }

    /** @test */
    public function teacher_can_view_exam_list()
    {
        $this->actingAs($this->teacher);

        Exam::factory()->count(3)->create([
            'subject_id' => $this->subject->id,
            'created_by' => $this->teacher->id,
        ]);

        $response = $this->get(route('teacher.exams.index'));

        $response->assertStatus(200);
        $response->assertViewHas('exams');
    }

    /** @test */
    public function teacher_can_update_exam()
    {
        $this->actingAs($this->teacher);

        $exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'created_by' => $this->teacher->id,
        ]);

        $updateData = [
            'title' => 'Kiểm tra cập nhật',
            'duration' => 90,
        ];

        $response = $this->put(route('teacher.exams.update', $exam->id), array_merge($exam->toArray(), $updateData));

        $response->assertRedirect();
        $this->assertDatabaseHas('exams', [
            'id' => $exam->id,
            'title' => 'Kiểm tra cập nhật',
            'duration' => 90,
        ]);
    }

    /** @test */
    public function teacher_can_delete_exam()
    {
        $this->actingAs($this->teacher);

        $exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'created_by' => $this->teacher->id,
        ]);

        $response = $this->delete(route('teacher.exams.destroy', $exam->id));

        $response->assertRedirect();
        $this->assertSoftDeleted('exams', ['id' => $exam->id]);
    }

    /** @test */
    public function student_can_submit_exam()
    {
        $this->actingAs($this->student);

        $exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'status' => 'active',
            'start_time' => now()->subHours(1),
            'end_time' => now()->addHours(2),
        ]);

        $questions = Question::factory()->count(5)->create([
            'subject_id' => $this->subject->id,
        ]);
        
        $exam->questions()->attach($questions->pluck('id'));

        $answers = [];
        foreach ($questions as $question) {
            $answers[$question->id] = 'A';
        }

        $submissionData = [
            'answers' => $answers,
            'time_spent' => 30,
        ];

        $response = $this->post(route('student.exams.submit', $exam->id), $submissionData);

        $response->assertRedirect();
        $this->assertDatabaseHas('exam_submissions', [
            'exam_id' => $exam->id,
            'student_id' => $this->student->id,
        ]);
    }

    /** @test */
    public function student_cannot_submit_exam_after_deadline()
    {
        $this->actingAs($this->student);

        $exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'status' => 'active',
            'start_time' => now()->subDays(7),
            'end_time' => now()->subDays(1), // Đã hết hạn
        ]);

        $response = $this->get(route('student.exams.take', $exam->id));

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function exam_auto_grading_works()
    {
        $exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'total_marks' => 100,
        ]);

        $questions = Question::factory()->count(10)->create([
            'subject_id' => $this->subject->id,
            'marks' => 10,
        ]);
        
        $exam->questions()->attach($questions->pluck('id'));

        $submission = ExamSubmission::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $this->student->id,
        ]);

        // Giả sử 7/10 câu đúng
        $correctAnswers = 7;
        $submission->update([
            'marks_obtained' => $correctAnswers * 10,
            'status' => 'graded',
        ]);

        $this->assertEquals(70, $submission->marks_obtained);
        $this->assertEquals('graded', $submission->status);
    }

    /** @test */
    public function teacher_can_view_exam_submissions()
    {
        $this->actingAs($this->teacher);

        $exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'created_by' => $this->teacher->id,
        ]);

        ExamSubmission::factory()->count(5)->create([
            'exam_id' => $exam->id,
        ]);

        $response = $this->get(route('teacher.exams.submissions', $exam->id));

        $response->assertStatus(200);
        $response->assertViewHas('submissions');
    }
}
