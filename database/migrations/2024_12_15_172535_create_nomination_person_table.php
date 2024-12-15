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
        Schema::create('nomination_person', function (Blueprint $table) {
            $table->foreignId('nomination_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('persons')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomination_person');
    }
};
