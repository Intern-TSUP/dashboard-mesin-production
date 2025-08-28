<?php

namespace Database\Seeders;

use App\Models\Line;
use App\Models\DepartemenHris;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua departemen yang merupakan "Line"
        $productionLines = DepartemenHris::isLine()->get();

        // 2. Lakukan perulangan dan update atau buat data di tabel lines
        foreach ($productionLines as $dept) {
            Line::updateOrCreate([
                'name' => $dept->OrgName,
                'empOrg' => $dept->EmpOrg,
                'inupby' => 'tsup@kalbe.co.id'
            ]);
        }
    }
}
