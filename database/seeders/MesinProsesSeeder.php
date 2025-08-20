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
        $prosesMixing = Proses::where('name', 'Mixing')->first();
        $prosesWetGranulationDrying = Proses::where('name', 'Wet Granulation & Drying')->first();
        $prosesCampurMassa = Proses::where('name', 'Campur Massa')->first();
        $prosesDryGranulation = Proses::where('name', 'Dry Granulation')->first();
        $prosesSieving = Proses::where('name', 'Sieving')->first();

        //ambil data mesin
        $mesinServolift = Mesin::where('kodeMesin', 'M001')->first();
        $mesinIbcServolift = Mesin::where('kodeMesin', 'M002')->first();
        $mesinHdgcHuttlin = Mesin::where('kodeMesin', 'M003')->first();
        $mesinQuadrocomill = Mesin::where('kodeMesin', 'M004')->first();

        // menghubungkan proses dengan mesin
        if ($prosesWetGranulationDrying && $mesinHdgcHuttlin) {
            $prosesWetGranulationDrying->mesin()->attach($mesinHdgcHuttlin->id);
        }

        if ($prosesMixing && $mesinServolift && $mesinIbcServolift) {
            $prosesMixing->mesin()->attach([$mesinServolift->id, $mesinIbcServolift->id]);
        }

        if ($prosesCampurMassa && $mesinServolift && $mesinIbcServolift) {
            $prosesCampurMassa->mesin()->attach([$mesinServolift->id, $mesinIbcServolift->id]);
        }

        if ($prosesSieving && $mesinQuadrocomill) {
            $prosesSieving->mesin()->attach([$mesinQuadrocomill->id]);
        }
    }
}
