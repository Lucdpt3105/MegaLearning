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
        Schema::table('answers', function (Blueprint $table) {
            $table->foreignId('question_id')->after('id')->constrained()->onDelete('cascade');
            $table->text('content')->after('question_id'); // Nội dung đáp án
            $table->boolean('is_correct')->default(false)->after('content'); // Đáp án đúng
            $table->integer('order')->default(0)->after('is_correct'); // Thứ tự hiển thị (A, B, C, D)
            $table->string('image_url')->nullable()->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropColumn(['question_id', 'content', 'is_correct', 'order', 'image_url']);
        });
    }
};
