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
        Schema::table('questions', function (Blueprint $table) {
            $table->text('content')->after('id'); // Nội dung câu hỏi
            $table->enum('type', ['multiple_choice', 'essay'])->default('multiple_choice')->after('content');
            $table->foreignId('exam_id')->nullable()->constrained()->onDelete('cascade')->after('type'); // Null nếu trong ngân hàng
            $table->foreignId('subject_id')->constrained()->onDelete('cascade')->after('exam_id');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->after('subject_id');
            $table->decimal('points', 5, 2)->default(1)->after('created_by');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium')->after('points');
            $table->text('explanation')->nullable()->after('difficulty'); // Giải thích đáp án
            $table->string('image_url')->nullable()->after('explanation');
            $table->integer('order')->default(0)->after('image_url'); // Thứ tự trong đề thi
            $table->boolean('in_question_bank')->default(false)->after('order'); // Có trong ngân hàng câu hỏi không
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'content', 'type', 'exam_id', 'subject_id', 'created_by', 
                'points', 'difficulty', 'explanation', 'image_url', 'order', 'in_question_bank'
            ]);
        });
    }
};
