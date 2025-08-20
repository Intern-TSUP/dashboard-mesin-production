<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Line;


class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cari user yang ingin dihubungkan
        $supervisor = User::where('jobLvl', 'Operator')->first();

        // 2. Cari line yang akan dihubungkan
        $line8 = Line::where('name', 'Line 8')->first();

        // 3. Pastikan keduanya ditemukan, baru buat relasinya
        if ($supervisor && $line8) {
            // Gunakan relasi 'profile()' yang sudah kita buat di model User
            // untuk membuat entri baru di tabel 'user_profiles'.
            $supervisor->profile()->create([
                'line_id' => $line8->id,
            ]);
        }
    }
}
