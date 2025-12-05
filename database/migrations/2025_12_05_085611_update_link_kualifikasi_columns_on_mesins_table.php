<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mesins', function (Blueprint $table) {
            // Rename kolom lama menjadi link_kualifikasi_1
            if (Schema::hasColumn('mesins', 'link_kualifikasi')) {
                $table->renameColumn('link_kualifikasi', 'link_kualifikasi_1');
            }

            // Tambahkan kolom link_kualifikasi_2 sampai 5
            $table->string('link_kualifikasi_2')->nullable()->after('link_kualifikasi_1');
            $table->string('link_kualifikasi_3')->nullable()->after('link_kualifikasi_2');
            $table->string('link_kualifikasi_4')->nullable()->after('link_kualifikasi_3');
            $table->string('link_kualifikasi_5')->nullable()->after('link_kualifikasi_4');
        });
    }

    public function down(): void
    {
        Schema::table('mesins', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn([
                'link_kualifikasi_2',
                'link_kualifikasi_3',
                'link_kualifikasi_4',
                'link_kualifikasi_5',
            ]);

            // Rename kembali jika kolom sudah ada
            if (Schema::hasColumn('mesins', 'link_kualifikasi_1')) {
                $table->renameColumn('link_kualifikasi_1', 'link_kualifikasi');
            }
        });
    }
};
