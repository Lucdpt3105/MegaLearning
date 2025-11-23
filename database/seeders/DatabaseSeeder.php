<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chạy seeder theo thứ tự: Roles -> Users -> Subjects -> Private Chat
        $this->call([
<<<<<<< .merge_file_uChAAo
            // RoleSeeder::class,
            // UserSeeder::class,
           // ForumSeeder::class,
=======
            RolePermissionSeeder::class,
            UserSeeder::class,
            SubjectSeeder::class,
            PrivateChatSeeder::class,
>>>>>>> .merge_file_zikVx1
        ]);
    }
}
