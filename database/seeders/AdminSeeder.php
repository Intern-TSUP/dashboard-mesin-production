<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Roles;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Route;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menentukan role Administrator
        $adminRole = Roles::firstOrCreate([
            'name' => 'Administrator',
            // 'slug_name' => 'administrator',
        ]);

        // Menambahkan user Administrator
        User::create([
            'employeId' => '000000000',
            'empTypeGroup' => 'PKWTT',
            'fullname' => 'SuperAdmin',
            'email' => 'tsup@kalbe.co.id',
            'jobLvl' => 'Administrator',
            'jobTitle' => 'Administrator',
            'deptKode' => '010701010601001002050000',
            'groupName' => 'Cikarang',
            'groupKode' => 'KF.9999',
            'password' => Hash::make('123')
        ]);

        $routes = Route::getRoutes()->getRoutesByName();

        foreach ($routes as $routeName => $route) {
            // Simpan routeName dan URL ke tabel permissions
            Permissions::create([
                'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                'role_id' => $adminRole->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }
    }
}
