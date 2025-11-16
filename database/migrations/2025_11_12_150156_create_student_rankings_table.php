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
        Schema::create('student_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_room_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('gpa', 5, 2)->default(0); // Grade Point Average
            $table->integer('rank')->default(0); // Xếp hạng
            $table->integer('total_students')->default(0); // Tổng số học sinh trong lớp
            $table->decimal('average_score', 5, 2)->default(0);
            $table->integer('total_exams_taken')->default(0);
            $table->integer('total_exams_passed')->default(0);
            $table->decimal('attendance_rate', 5, 2)->default(0); // Tỷ lệ điểm danh (%)
            $table->timestamp('calculated_at')->useCurrent();
            $table->timestamps();

            $table->index(['student_id', 'class_room_id']);
            $table->index(['class_room_id', 'rank']);
            $table->index(['subject_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_rankings');
    }
};
