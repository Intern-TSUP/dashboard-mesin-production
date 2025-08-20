<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleEmployee::class,
            PermissionEmployee::class,
            MaintenanceSeeder::class,
            LineSeeder::class,
            PermissionLine::Class,
            MesinSeeder::class,
            ProsesSeeder::class,
            MesinProsesSeeder::class,
            GroupSeeder::class,
            AdminSeeder::class,
            UserProfileSeeder::class
        ]);
    }
}
