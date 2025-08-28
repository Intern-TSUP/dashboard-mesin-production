<?php

namespace Database\Seeders;

use App\Models\Mesin;
use App\Models\Proses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesinProsesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        //ambil data proses
        // $prosesMixing = Proses::where('name', 'Mixing')->first();
        // $prosesWetGranulationDrying = Proses::where('name', 'Wet Granulation & Drying')->first();
        // $prosesCampurMassa = Proses::where('name', 'Campur Massa')->first();
        // $prosesDryGranulation = Proses::where('name', 'Dry Granulation')->first();
        // $prosesSieving = Proses::where('name', 'Sieving')->first();
    }
}
