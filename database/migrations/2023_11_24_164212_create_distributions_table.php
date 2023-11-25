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
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['LOCAL', 'COUNTRY_SPECIFIC', 'PREMIERE', 'ALL', 'WORLD_PREMIER']);
            $table->enum('sub_type', ['CINEMA', 'DVD', 'DIGITAL', 'BLURAY'])->nullable();
            $table->date('date')->nullable();
            $table->boolean('re_release')->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};
