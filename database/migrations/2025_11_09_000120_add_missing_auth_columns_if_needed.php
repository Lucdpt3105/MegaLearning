<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            // Thêm các cột cần cho đăng nhập email nếu thiếu
            if (!Schema::hasColumn('users', 'email')) {
                if (Schema::hasColumn('users', 'name')) {
                    $table->string('email')->nullable()->index()->after('name');
                } else {
                    $table->string('email')->nullable()->index();
                }
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
        });
    }

    public function down(): void
    {
        // Không nên tự ý drop các cột auth vì có thể đang sử dụng.
    }
};
