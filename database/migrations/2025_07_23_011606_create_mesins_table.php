<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mesins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kodeMesin')->unique();
            $table->string('name');
            $table->string('kapasitas');
            $table->string('satuanKapasitas');
            $table->string('speed');
            $table->string('satuanSpeed');
            $table->integer('jumlahOperator')->default(2);
            $table->text('keterangan')->nullable();
            $table->string('link_kualifikasi')->nullable();
            $table->string('image')->nullable();
            $table->foreignUuid('line_id')->nullable()->constrained('lines')->onDelete('set null');
            // $table->foreignId('departement_id')->nullable()->constrained('department_hris');
            $table->string('inupby');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesin');
    }
};
