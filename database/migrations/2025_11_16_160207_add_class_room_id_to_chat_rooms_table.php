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
        Schema::table('chat_rooms', function (Blueprint $table) {
            // Thêm class_room_id để mỗi lớp có chat room riêng
            $table->unsignedBigInteger('class_room_id')->nullable()->after('subject_id');
            $table->foreign('class_room_id')->references('id')->on('class_rooms')->onDelete('cascade');
            
            // Cập nhật room_type để thêm loại 'class'
            // 'group', 'private', 'subject', 'class'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropForeign(['class_room_id']);
            $table->dropColumn('class_room_id');
        });
    }
};
