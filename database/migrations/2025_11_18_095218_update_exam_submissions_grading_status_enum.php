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
        // MySQL doesn't support altering enum directly, so we need to use raw SQL
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
