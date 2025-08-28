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
                        'url' => $routeName,
                        'line_id' => $item->id
                    ]);
                }
            }
            PermissionsLine::create([
                'url' => 'v1.dashboard',
                'line_id' => $item->id
            ]);
            PermissionsLine::create([
                'url' => 'v1.auditTrail',
                'line_id' => $item->id
            ]);
            PermissionsLine::create([
                'url' => 'v1.contactUs',
                'line_id' => $item->id
            ]);
        }
    }
}
