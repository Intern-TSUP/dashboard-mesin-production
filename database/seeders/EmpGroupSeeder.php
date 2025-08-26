<?php

namespace Database\Seeders;

use App\Models\DepartemenGroupHris;
use App\Models\DepartemenHris;
use Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $url = 'https://api-pharma.kalbe.co.id/v1/ListDept';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-Key' => 'SQA45CsPgqRCeyoO0ZzeKK6BFG1vpR1vy7r-gvPiEw4',
        ])->get($url)->json();


        $usedGroupNames = []; // penampung OrgGroupName yang sudah dipakai
        foreach ($response as $item) {
            // Filter perusahaan
            if (
                (isset($item['EmpCompany']) && $item['EmpCompany'] === '01') ||
                (isset($item['CompName']) && $item['CompName'] === 'PT. Kalbe Farma Tbk.')
            ) {
                $groupName = $item['OrgGroupName'] ?? null;

                // Skip jika groupName sudah pernah dimasukkan
                if ($groupName && in_array($groupName, $usedGroupNames)) {
                    continue;
                }

                // Insert data
                DepartemenGroupHris::create([
                    'OrgGroupName' => $groupName,
                    'EmpCompany' => $item['EmpCompany'],
                    'CompName' => $item['CompName'],
                ]);

                // Tandai sudah dipakai
                if ($groupName) {
                    $usedGroupNames[] = $groupName;
                }
            }
        }

        foreach ($response as $item) {
            // Filter berdasarkan EmpCompany = 01 atau CompName = PT. Kalbe Farma Tbk.
            if (
                isset($item['EmpCompany']) && $item['EmpCompany'] === '01' ||
                isset($item['CompName']) && $item['CompName'] === 'PT. Kalbe Farma Tbk.'
            ) {
                DepartemenHris::create([
                    'EmpOrg' => $item['EmpOrg'],
                    'OrgName' => $item['OrgName'],
                    'OrgGroup' => $item['OrgGroup'],
                    'OrgGroupName' => $item['OrgGroupName'],
                    'EmpCompany' => $item['EmpCompany'],
                    'CompName' => $item['CompName'],
                ]);
            }
        }

        // foreach ($response as $item) {
        //     if (
        //         isset($item['EmpOrg'] === '010701010601001002050000')
        //     ) { 
                
        //     }
        // }
    }
}
