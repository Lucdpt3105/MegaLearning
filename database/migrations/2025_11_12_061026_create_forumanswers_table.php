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
        Schema::create('forumanswers', function (Blueprint $table) {
            $table->id('forum_answer_id');
            $table->unsignedBigInteger('forum_question_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->text('answer_content')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Foreign Keys
            $table->foreign('forum_question_id')
                  ->references('forum_question_id')
                  ->on('forumquestions')
                  ->onDelete('cascade'); 

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            $table->foreign('parent_id')->references('forum_answer_id')->on('forumanswers')->onDelete('cascade');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forumanswers');
    }
};
