<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'ai' role to the system
        Role::firstOrCreate(['name' => 'ai']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'ai' role
        Role::where('name', 'ai')->delete();
    }
};
