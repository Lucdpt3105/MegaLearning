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
        
        // Update ENUM to add new value
        DB::statement("ALTER TABLE exam_submissions MODIFY COLUMN grading_status ENUM('pending', 'partially_graded', 'graded', 'auto_graded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE exam_submissions MODIFY COLUMN grading_status ENUM('pending', 'graded', 'auto_graded') DEFAULT 'pending'");
    }
};
