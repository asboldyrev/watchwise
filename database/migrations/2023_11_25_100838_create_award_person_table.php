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
        Schema::create('award_person', function (Blueprint $table) {
            $table->foreignId('award_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('persons')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('award_person');
    }
};
