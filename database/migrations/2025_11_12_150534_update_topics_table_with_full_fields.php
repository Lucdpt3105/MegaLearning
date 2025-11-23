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
        Schema::table('topics', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade')->after('description');
            $table->integer('order')->default(0)->after('subject_id');
            $table->integer('duration')->nullable()->comment('Thời lượng học (phút)')->after('order');
            $table->json('resources')->nullable()->after('duration'); // Tài nguyên học tập
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn(['name', 'description', 'subject_id', 'order', 'duration', 'resources']);
        });
    }
};
