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
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 2',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 3',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 4',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 5',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 6',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 7',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 8',
            'inupby' => 'tsup@kalbe.co.id'
        ]);

        Line::create([
            'name' => 'Line 9',
            'inupby' => 'tsup@kalbe.co.id'
        ]);
    }
}
