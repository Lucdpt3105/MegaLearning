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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // Nội dung câu hỏi
            $table->enum('type', ['multiple_choice', 'true_false', 'essay', 'fill_blank'])->default('multiple_choice'); // Loại câu hỏi
            $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('cascade'); // Liên kết với đề thi (nullable = câu hỏi trong ngân hàng)
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade'); // Môn học
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Người tạo
            $table->decimal('points', 5, 2)->default(1.00); // Điểm số
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium'); // Độ khó
            $table->text('explanation')->nullable(); // Giải thích đáp án
            $table->string('image_url')->nullable(); // Hình ảnh câu hỏi
            $table->integer('order')->default(0); // Thứ tự câu hỏi trong đề thi
            $table->boolean('in_question_bank')->default(true); // Có trong ngân hàng câu hỏi không
            $table->timestamps();

            $table->index(['subject_id', 'in_question_bank']);
            $table->index(['exam_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
