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
        $line1 = Line::firstOrCreate([
            'name' => 'Line 1',
            'inupby' => 'tsup@kalbe.co.id',
        ]);

        $line2 = Line::firstOrCreate([
            'name' => 'Line 2',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        $line3 = Line::firstOrCreate([
            'name' => 'Line 3',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M001'],
            [
                'name' => 'Servolift',
                'kapasitas' => 'Max. 150kg, 50L-400L',
                'speed' => '5-20 rpm',
                'line_id' => $line1->id,
                'inupby' => 'tsup@kalbe.co.id',
            ]
        );

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M002'],
            [
                'name' => 'IBC Servolift',
                'kapasitas' => '800L',
                'line_id' => $line1->id,
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M003'],
            [
                'name' => 'HDGC 100 (Huttlin)',
                'kapasitas' => 'Max. 100kg, Max. 250L, Max. Air flow 2000 m3/jam',
                'line_id' => $line2->id,
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );

        Mesin::updateOrCreate(
            ['kodeMesin' => 'M004'],
            [
                'name' => 'M0801 Quadrocomill U-20 (1)',
                'speed' => '450-2700 rpm',
                'line_id' => $line3->id,
                'inupby' => 'tsup@kalbe.co.id'
            ]
        );
    }
}