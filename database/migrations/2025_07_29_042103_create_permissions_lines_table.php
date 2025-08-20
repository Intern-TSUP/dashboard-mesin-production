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
        Schema::create('permissions_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('line_id');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->string('url'); // URL yang diizinkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions_lines');
    }
};
