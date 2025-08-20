<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;

class RoleEmployee extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $text = 'https://api-pharma.kalbe.co.id/v1/ListJobTitleName';
        $text = 'https://api-pharma.kalbe.co.id/v1/ListJobLvlName';
        // $text = 'https://api-pharma.kalbe.co.id/v1/ListUsers';

        $response = \Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-Key' => 'SQA45CsPgqRCeyoO0ZzeKK6BFG1vpR1vy7r-gvPiEw4',
        ])->get($text);
        $response = $response->json();

        foreach ($response as $key => $value) {
            Roles::create([
                'name' => $value,
            ]);
        }

        // Add Custom Roles Here
        // Roles::create(['name' => 'OUTSTANDING VERIFIKATOR']);
        // Roles::create(['name' => 'REVISI VERIFIKATOR']);
    }
}
