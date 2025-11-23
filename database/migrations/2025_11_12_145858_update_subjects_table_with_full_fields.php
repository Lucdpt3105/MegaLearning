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
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('code')->unique()->after('name');
            $table->text('description')->nullable()->after('code');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('description');
            $table->enum('status', ['draft', 'active', 'archived'])->default('active')->after('teacher_id');
            $table->json('settings')->nullable()->after('status'); // Cài đặt môn học
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['name', 'code', 'description', 'teacher_id', 'status', 'settings']);
        });
    }
};
