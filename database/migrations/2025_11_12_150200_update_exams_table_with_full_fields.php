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
        Schema::table('exams', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade')->after('description');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->after('subject_id');
            $table->enum('type', ['multiple_choice', 'essay', 'mixed'])->default('multiple_choice')->after('created_by');
            $table->integer('duration')->comment('Thời gian làm bài (phút)')->after('type');
            $table->integer('total_questions')->default(0)->after('duration');
            $table->decimal('total_points', 5, 2)->default(0)->after('total_questions');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('total_points');
            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('rejection_reason');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->dateTime('start_time')->nullable()->after('approved_at');
            $table->dateTime('end_time')->nullable()->after('start_time');
            $table->boolean('allow_retake')->default(false)->after('end_time');
            $table->integer('max_attempts')->default(1)->after('allow_retake');
            $table->boolean('shuffle_questions')->default(false)->after('max_attempts');
            $table->boolean('shuffle_answers')->default(false)->after('shuffle_questions');
            $table->boolean('show_results_immediately')->default(false)->after('shuffle_answers');
            $table->json('settings')->nullable()->after('show_results_immediately');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'title', 'description', 'subject_id', 'created_by', 'type', 'duration',
                'total_questions', 'total_points', 'approval_status', 'rejection_reason',
                'approved_by', 'approved_at', 'start_time', 'end_time', 'allow_retake',
                'max_attempts', 'shuffle_questions', 'shuffle_answers', 'show_results_immediately', 'settings'
            ]);
        });
    }
};
