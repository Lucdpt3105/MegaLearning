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
        // Chạy seeder theo thứ tự: Roles -> Users
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
