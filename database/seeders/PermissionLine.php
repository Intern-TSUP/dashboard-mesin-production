<?php

namespace Database\Seeders;

use App\Models\PermissionsLine;
use App\Models\Line;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Route;

class PermissionLine extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = Route::getRoutes()->getRoutesByName();
        $line = Line::latest()->get();

        foreach ($line as $item) {
            foreach ($routes as $routeName => $route) {
                // Cek apakah route memiliki prefix "v1"
                if (str_starts_with($route->getPrefix(), 'v1')) {
                    // Simpan routeName dan URL ke tabel permissions
                    PermissionsLine::create([
                        'url' => $routeName, // Menggunakan nama rute sebagai identifikasi
                        'line_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
                    ]);
                }
            }
            PermissionsLine::create([
                'url' => 'v1.dashboard', // Menggunakan nama rute sebagai identifikasi
                'line_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
            PermissionsLine::create([
                'url' => 'v1.auditTrail', // Menggunakan nama rute sebagai identifikasi
                'line_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
            PermissionsLine::create([
                'url' => 'v1.contactUs', // Menggunakan nama rute sebagai identifikasi
                'line_id' => $item->id // Set default jobLvl, ini dapat diubah sesuai kebutuhan Anda
            ]);
        }
    }
}
