<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('attempt_number')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->integer('time_spent')->nullable()->comment('Thời gian làm bài (giây)');
            $table->json('answers')->nullable(); // Lưu câu trả lời của học sinh
            $table->string('essay_file_path')->nullable(); // File bài làm tự luận
            $table->decimal('score', 5, 2)->nullable();
            $table->enum('grading_status', ['pending', 'graded', 'auto_graded'])->default('pending');
            $table->text('feedback')->nullable(); // Nhận xét của giáo viên
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('graded_at')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'timeout'])->default('in_progress');
            $table->timestamps();

            $table->index(['exam_id', 'student_id']);
            $table->unique(['exam_id', 'student_id', 'attempt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_submissions');
    }
};
