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
        Schema::create('departemen_group_hris', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('OrgGroupName')->nullable();
            $table->string('EmpCompany');
            $table->string('CompName');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departemen_group_hris');
    }
};
