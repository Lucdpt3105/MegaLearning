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
        Schema::create('forumquestions', function (Blueprint $table) {
            $table->id('forum_question_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title', 200)->nullable();
            $table->string('content')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Foreign Key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forumquestions');
    }
};
