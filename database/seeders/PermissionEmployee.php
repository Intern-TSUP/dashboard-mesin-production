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
                if ($route->getName() && str_starts_with($route->getName(), 'v1.dashboard')) {
                    
                    // Pastikan variabel $routeName diisi dengan nama rute
                    $routeName = $route->getName(); 

                    // Simpan routeName dan URL ke tabel permissions
                    Permissions::create([
                        'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                        'role_id' => $item->id 
                    ]);
                }

                if ($route->getName() && str_starts_with($route->getName(), 'v1.auditTrail')) {
                    
                    // Pastikan variabel $routeName diisi dengan nama rute
                    $routeName = $route->getName(); 

                    // Simpan routeName dan URL ke tabel permissions
                    Permissions::create([
                        'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                        'role_id' => $item->id 
                    ]);
                }
            }

            Permissions::create([
                'url' => 'v1.contactUs.index', // Menggunakan nama rute sebagai identifikasi
                'role_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }
    }
}
