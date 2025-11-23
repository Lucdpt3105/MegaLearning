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
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id', 50)->nullable()->after('id'); // Mã học sinh
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('name'); // Giới tính
            $table->date('date_of_birth')->nullable()->after('gender'); // Ngày sinh
            $table->text('address')->nullable()->after('bio'); // Địa chỉ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'gender', 'date_of_birth', 'address']);
        });
    }
};
