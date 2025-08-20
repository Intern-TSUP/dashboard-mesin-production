<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Route;

class PermissionEmployee extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::getRoutes()->getRoutesByName();
        $role = Roles::latest()->get();

        foreach ($role as $item) {
            foreach ($routes as $routeName => $route) {
                // Cek apakah route memiliki prefix "v1"
                if (str_starts_with($route->getPrefix(), 'v1.dashboard')) {
                    // Simpan routeName dan URL ke tabel permissions
                    Permissions::create([
                        'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                        'role_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
                    ]);
                }   
            }
            Permissions::create([
                'url' => 'v1.dashboard', // Menggunakan nama rute sebagai identifikasi
                'role_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);

            Permissions::create([
                'url' => 'v1.detail', // Menggunakan nama rute sebagai identifikasi
                'role_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);

            Permissions::create([
                'url' => 'v1.auditTrail', // Menggunakan nama rute sebagai identifikasi
                'role_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);

            Permissions::create([
                'url' => 'v1.contactUs', // Menggunakan nama rute sebagai identifikasi
                'role_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }
    }
}
