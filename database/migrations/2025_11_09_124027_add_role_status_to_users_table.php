<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Đổi user_name -> name (nếu có)
            if (Schema::hasColumn('users', 'user_name') && !Schema::hasColumn('users', 'name')) {
                $table->renameColumn('user_name', 'name'); // cần doctrine/dbal
            }

            // Thêm role, status nếu thiếu
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }

            // Xoá các cột dư thừa nếu tồn tại
            if (Schema::hasColumn('users', 'user_email')) {
                $table->dropColumn('user_email');
            }
            if (Schema::hasColumn('users', 'user_password')) {
                $table->dropColumn('user_password');
            }
            if (Schema::hasColumn('users', 'user_role')) {
                $table->dropColumn('user_role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Khôi phục lại như cũ (tuỳ nhu cầu)
            if (Schema::hasColumn('users', 'name') && !Schema::hasColumn('users', 'user_name')) {
                $table->renameColumn('name', 'user_name');
            }
            if (Schema::hasColumn('users', 'role'))   $table->dropColumn('role');
            if (Schema::hasColumn('users', 'status')) $table->dropColumn('status');

            // Thêm lại các cột cũ (nếu cần rollback)
            if (!Schema::hasColumn('users', 'user_email'))    $table->string('user_email')->nullable();
            if (!Schema::hasColumn('users', 'user_password')) $table->string('user_password')->nullable();
            if (!Schema::hasColumn('users', 'user_role'))     $table->string('user_role')->nullable();
        });
    }
};

