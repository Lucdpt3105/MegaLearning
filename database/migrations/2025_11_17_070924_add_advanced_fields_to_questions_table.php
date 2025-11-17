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
            // Add topic_id for chapter/lesson organization
            $table->foreignId('topic_id')->nullable()->after('subject_id')->constrained('topics')->onDelete('set null');
            
            // Add Bloom's Taxonomy levels (Mức độ nhận thức)
            $table->enum('bloom_level', ['remember', 'understand', 'apply', 'analyze'])->default('remember')->after('difficulty');
            // remember = Nhận biết, understand = Thông hiểu, apply = Vận dụng, analyze = Vận dụng cao
            
            // Add tags for better categorization
            $table->json('tags')->nullable()->after('bloom_level');
            
            // Add media support
            $table->string('audio_url')->nullable()->after('image_url');
            $table->string('video_url')->nullable()->after('audio_url');
            
            // Add answer_count for multiple choice questions
            $table->integer('correct_answer_count')->default(1)->after('type');
            // 1 = single answer, >1 = multiple answers
            
            // Add grading guide for essay questions
            $table->text('grading_guide')->nullable()->after('explanation');
            
            // Add usage statistics
            $table->integer('usage_count')->default(0)->after('in_question_bank');
            $table->timestamp('last_used_at')->nullable()->after('usage_count');
            
            // Add indexes for better performance
            $table->index(['topic_id', 'bloom_level']);
            $table->index('usage_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropIndex(['topic_id', 'bloom_level']);
            $table->dropIndex(['usage_count']);
            $table->dropColumn([
                'topic_id',
                'bloom_level',
                'tags',
                'audio_url',
                'video_url',
                'correct_answer_count',
                'grading_guide',
                'usage_count',
                'last_used_at'
            ]);
        });
    }
};
