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
        Schema::table('exams', function (Blueprint $table) {
            // Add missing fields that are in the new schema but not in old table
            if (!Schema::hasColumn('exams', 'class_room_id')) {
                $table->foreignId('class_room_id')->nullable()->after('subject_id')->constrained('class_rooms')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('exams', 'passing_score')) {
                $table->decimal('passing_score', 5, 2)->nullable()->after('total_points');
            }
            
            if (!Schema::hasColumn('exams', 'allow_review')) {
                $table->boolean('allow_review')->default(true)->after('show_results_immediately');
            }
            
            if (!Schema::hasColumn('exams', 'status')) {
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->after('allow_review');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['class_room_id']);
            $table->dropColumn(['class_room_id', 'passing_score', 'allow_review', 'status']);
        });
    }
};
