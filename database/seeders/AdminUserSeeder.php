<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Config qua .env nếu có
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'Admin@12345');

        // Nếu bảng users chưa tồn tại thì bỏ qua
        if (!Schema::hasTable('users')) {
            $this->command?->warn('Bảng users chưa tồn tại, skip seeding.');
            return;
        }

        // Kiểm tra đã có admin theo "tiêu chí tốt nhất có thể"
        $query = DB::table('users');
        if (Schema::hasColumn('users', 'email')) {
            $query->where('email', $email);
        } elseif (Schema::hasColumn('users', 'username')) {
            $query->where('username', 'admin');
        } elseif (Schema::hasColumn('users', 'role')) {
            $query->where('role', 'admin');
        }
        $exists = $query->exists();

        if ($exists) {
            $this->command?->info('Admin đã tồn tại, skip.');
            return;
        }

        // Xây mảng dữ liệu chỉ với các cột THỰC SỰ tồn tại
        $data = [];

        if (Schema::hasColumn('users', 'name')) {
            $data['name'] = 'Super Admin';
        }
        if (Schema::hasColumn('users', 'username')) {
            $data['username'] = 'admin';
        }
        if (Schema::hasColumn('users', 'email')) {
            $data['email'] = $email;
        }
        if (Schema::hasColumn('users', 'role')) {
            $data['role'] = 'admin';
        }
        if (Schema::hasColumn('users', 'email_verified_at')) {
            $data['email_verified_at'] = now();
        }
        if (Schema::hasColumn('users', 'password')) {
            $data['password'] = Hash::make($password);
        }
        if (Schema::hasColumn('users', 'remember_token')) {
            $data['remember_token'] = Str::random(10);
        }
        if (Schema::hasColumn('users', 'created_at')) {
            $data['created_at'] = now();
        }
        if (Schema::hasColumn('users', 'updated_at')) {
            $data['updated_at'] = now();
        }

        // Chèn bản ghi
        DB::table('users')->insert($data);

        $this->command?->info('Đã tạo admin mặc định thành công.');
    }
}
