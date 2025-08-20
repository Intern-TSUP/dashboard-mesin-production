<?php

namespace Database\Seeders;

use App\Models\Proses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProsesSeeder extends Seeder
{
    public function run(): void
    {
        Proses::updateOrCreate(
            ['name' => 'Mixing'],
            ['inupby' => 'tsup@kalbe.co.id']
        );

        Proses::updateOrCreate(
            ['name' => 'Wet Granulation & Drying'],
            ['inupby' => 'tsup@kalbe.co.id']
        );

        Proses::updateOrCreate(
            ['name' => 'Campur Massa'],
            ['inupby' => 'tsup@kalbe.co.id']
        );

        Proses::updateOrCreate(
            ['name' => 'Dry Granulation'],
            ['inupby' => 'tsup@kalbe.co.id']
        );

        Proses::updateOrCreate(
            ['name' => 'Sieving'],
            ['inupby' => 'tsup@kalbe.co.id']
        );
    }
}