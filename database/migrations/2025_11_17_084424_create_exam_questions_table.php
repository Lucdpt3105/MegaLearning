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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->constrained()->onDelete('set null');
            
            $table->integer('order')->default(0);
            $table->decimal('points', 8, 2)->default(1);
            
            // For manually created questions (not from question bank)
            $table->text('custom_content')->nullable();
            $table->enum('custom_type', ['multiple_choice', 'true_false', 'essay', 'fill_blank'])->nullable();
            $table->json('custom_answers')->nullable();
            $table->text('custom_explanation')->nullable();
            
            $table->timestamps();
            
            $table->index(['exam_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
