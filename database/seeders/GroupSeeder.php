<?php

namespace Database\Seeders;

use App\Models\Group;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create([
            'kode'      => 'KF.9999',
            'name'      => 'Cikarang',
            'inupby'    => 'tsup@kalbe.co.id'
        ]);
    }
}
