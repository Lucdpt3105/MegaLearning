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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade'); // Giáo viên
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('room_code')->unique(); // Mã phòng để tham gia
            $table->string('meeting_url')->nullable(); // Link join meeting
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration')->nullable()->comment('Thời lượng thực tế (phút)');
            $table->string('recording_url')->nullable(); // Link video đã ghi
            $table->boolean('is_recording')->default(false);
            $table->enum('status', ['scheduled', 'in_progress', 'ended', 'cancelled'])->default('scheduled');
            $table->json('participants')->nullable(); // Danh sách người tham gia
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['class_room_id', 'status']);
            $table->index(['scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
};
