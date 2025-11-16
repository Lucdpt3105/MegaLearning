<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật ENUM để thêm giá trị 'class'
        DB::statement("ALTER TABLE chat_rooms MODIFY COLUMN room_type ENUM('group', 'private', 'subject', 'class') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Trở về ENUM ban đầu
        DB::statement("ALTER TABLE chat_rooms MODIFY COLUMN room_type ENUM('group', 'private', 'subject') NOT NULL");
    }
};
