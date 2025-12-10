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
        // Skip for SQLite (testing)
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        
        // Update type column to use new ENUM values
        if (Schema::hasColumn('exams', 'type')) {
            DB::statement("ALTER TABLE exams MODIFY COLUMN type ENUM('quiz', 'midterm', 'final', 'practice') NOT NULL DEFAULT 'quiz'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Revert back to old enum values
            DB::statement("ALTER TABLE exams MODIFY COLUMN type ENUM('multiple_choice', 'essay', 'mixed') NOT NULL DEFAULT 'multiple_choice'");
        });
    }
};
