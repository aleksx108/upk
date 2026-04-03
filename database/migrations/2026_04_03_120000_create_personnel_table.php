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
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('personal_code')->unique();
            $table->string('gender')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable()->unique();

            $table->string('country_code', 2)->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('street_number')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};