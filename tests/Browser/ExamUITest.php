<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExamUITest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function student_can_login_and_view_exam_list()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->browse(function (Browser $browser) use ($student) {
            $browser->visit('/login')
                    ->type('email', $student->email)
                    ->type('password', 'password')
                    ->press('Đăng nhập')
                    ->assertPathIs('/student/dashboard')
                    ->clickLink('Kỳ thi')
                    ->assertSee('Danh sách kỳ thi');
        });
    }

    /** @test */
    public function student_can_take_exam()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $subject = Subject::factory()->create(['teacher_id' => $teacher->id]);

        $exam = Exam::factory()->create([
            'subject_id' => $subject->id,
            'status' => 'active',
            'start_time' => now()->subHour(),
            'end_time' => now()->addHours(2),
        ]);

        $this->browse(function (Browser $browser) use ($student, $exam) {
            $browser->loginAs($student)
                    ->visit("/student/exams/{$exam->id}/take")
                    ->assertSee($exam->title)
                    ->assertSee('Thời gian còn lại')
                    ->radio('answers[1]', 'A')
                    ->radio('answers[2]', 'B')
                    ->press('Nộp bài')
                    ->assertSee('Nộp bài thành công')
                    ->assertPathIs('/student/exams/results');
        });
    }

    /** @test */
    public function teacher_can_create_exam()
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $subject = Subject::factory()->create(['teacher_id' => $teacher->id]);

        $this->browse(function (Browser $browser) use ($teacher, $subject) {
            $browser->loginAs($teacher)
                    ->visit('/teacher/exams/create')
                    ->type('title', 'Kiểm tra UI Test')
                    ->select('subject_id', $subject->id)
                    ->type('duration', '60')
                    ->type('total_marks', '100')
                    ->press('Tạo đề thi')
                    ->assertSee('Tạo đề thi thành công')
                    ->assertSee('Kiểm tra UI Test');
        });
    }

    /** @test */
    public function exam_timer_counts_down_properly()
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $subject = Subject::factory()->create(['teacher_id' => $teacher->id]);

        $exam = Exam::factory()->create([
            'subject_id' => $subject->id,
            'duration' => 60,
            'status' => 'active',
            'start_time' => now()->subMinutes(5),
            'end_time' => now()->addHour(),
        ]);

        $this->browse(function (Browser $browser) use ($student, $exam) {
            $browser->loginAs($student)
                    ->visit("/student/exams/{$exam->id}/take")
                    ->assertPresent('#timer')
                    ->pause(2000) // Wait 2 seconds
                    ->assertSeeIn('#timer', ':'); // Check timer format
        });
    }
}
