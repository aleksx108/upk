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
        Schema::create('workplaces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('personnel_id')->constrained('personnel');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('occupation_id')->constrained('occupations');

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workplaces');
    }
};
