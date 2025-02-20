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
        Schema::create('service_available_times', function (Blueprint $table) {
            $table->id();
            $table->date('starting_date');
            $table->date('ending_date');
            $table->timestamps();
            $table->foreignId('service_id')->nullable()->constrained('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_available_times');
    }
};
