<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            // Không tạo bảng mới ở đây để tránh đụng bảng sẵn có.
            return;
        }

        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'email')) {
                    $table->enum('role', ['user', 'admin'])->default('user')->after('email');
                } elseif (Schema::hasColumn('users', 'name')) {
                    $table->enum('role', ['user', 'admin'])->default('user')->after('name');
                } else {
                    $table->enum('role', ['user', 'admin'])->default('user');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
