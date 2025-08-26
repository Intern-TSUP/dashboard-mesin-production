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
        Schema::create('departemen_hris', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('EmpOrg')->nullable();
            $table->string('OrgName')->nullable();
            $table->string('OrgGroup')->nullable();
            $table->string('OrgGroupName')->nullable();
            $table->string('EmpCompany')->nullable();
            $table->string('CompName')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departemen_hris');
    }
};
