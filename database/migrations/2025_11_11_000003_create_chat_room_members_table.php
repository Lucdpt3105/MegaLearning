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
        Schema::create('chat_room_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['admin', 'member', 'bot'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign('room_id')
                  ->references('room_id')
                  ->on('chat_rooms')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Unique constraint: một user chỉ có thể join một room một lần
            $table->unique(['room_id', 'user_id']);

            // Indexes
            $table->index('room_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_room_members');
    }
};
