<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('compCode')->default('01');
            $table->string('employeId')->unique();
            $table->string('empTypeGroup');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('email_backup')->nullable();
            $table->string('phone')->nullable();
            $table->string('jobLvl');
            $table->string('jobTitle');
            $table->string('groupKode');
            $table->string('groupName');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->json('result')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('users', function (Blueprint $table) {
    //         $table->uuid('id')->primary();
    //         $table->string('nama');
    //         $table->string('username')->unique();
    //         $table->string('email')->unique();
    //         $table->string('password');
    //         $table->uuid('role_id')->nullable();
    //         $table->uuid('line_id')->nullable();
    //         $table->datetime('last_login_at')->nullable();
    //         $table->integer('failed_attempts')->default(0); // Default 0
    //         $table->boolean('is_blocked')->default(false);  // Default false
    //         $table->timestamps();
    //     });

    //     // Schema::create('password_reset_tokens', function (Blueprint $table) {
    //     //     $table->string('email')->primary();
    //     //     $table->string('token');
    //     //     $table->timestamp('created_at')->nullable();
    //     // });

    //     Schema::create('sessions', function (Blueprint $table) {
    //         $table->string('id')->primary();
    //         $table->uuid('user_id')->nullable()->index();
    //         $table->string('ip_address', 45)->nullable();
    //         $table->text('user_agent')->nullable();
    //         $table->longText('payload');
    //         $table->integer('last_activity')->index();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('users');
    //     // Schema::dropIfExists('password_reset_tokens');
    //     Schema::dropIfExists('sessions');
    // }
};
