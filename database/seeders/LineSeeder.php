<?php

namespace Database\Seeders;

use App\Models\Line;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Line::create([
            'name' => 'Line 1',
            'empOrg' => '010701010601000901010000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 2',
            'empOrg' => '010701010601000901060000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 3',
            'empOrg' => '010701010601000901030000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 4',
            'empOrg' => '010701010601000902010000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 5',
            'empOrg' => '010701010601000902050000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 6',
            'empOrg' => '010701010601001001070000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 7',
            'empOrg' => '010701010601000902070000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 8',
            'empOrg' => '010701010601001002050000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 9',
            'empOrg' => '010701010601001001080000',
            'inupby' => 'tsup@kalbe.co.id'
        ]);
    }
}
