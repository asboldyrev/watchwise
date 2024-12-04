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
        Schema::create('film_online_theater', function (Blueprint $table) {
            $table->foreignId('film_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('online_theater_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_online_theater');
    }
};
