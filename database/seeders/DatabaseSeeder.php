<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\PacientSeeder;
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
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            PacientSeeder::class,
            UserSeeder::class
        ]);
    }
}
