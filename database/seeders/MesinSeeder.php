<?php

namespace Database\Seeders;

use App\Models\Mesin;
use App\Models\Line;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesinSeeder extends Seeder
{
    public function run(): void
    {
        // $line1 = Line::firstOrCreate([
        //     'name' => 'Line 1',
        //     'inupby' => 'tsup@kalbe.co.id',
        // ]);

        // Mesin::updateOrCreate(
        //     ['kodeMesin' => 'M001'],
        //     [
        //         'name' => 'Servolift',
        //         'kapasitas' => 'Max. 150kg, 50L-400L',
        //         'satuanKapasitas' => 'Kilogram',
        //         'speed' => '5-20 rpm',
        //         'satuanSpeed' => 'Box/menit',
        //         'line_id' => $line1->id,
        //         'inupby' => 'tsup@kalbe.co.id',
        //     ]
        // );
    }
}