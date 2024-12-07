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
        Schema::create('related_films', function (Blueprint $table) {
            $table->foreignId('film_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('related_film_id')->constrained('films')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type', ['SEQUEL', 'PREQUEL', 'REMAKE', 'UNKNOWN']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('related_films');
    }
};
