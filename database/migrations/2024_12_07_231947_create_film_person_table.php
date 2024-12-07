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
        Schema::create('film_person', function (Blueprint $table) {
            $table->foreignId('film_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('persons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('profession_text')->nullable();
            $table->enum('profession_key', ['WRITER', 'OPERATOR', 'EDITOR', 'COMPOSER', 'PRODUCER_USSR', 'TRANSLATOR', 'DIRECTOR', 'DESIGN', 'PRODUCER', 'ACTOR', 'VOICE_DIRECTOR', 'UNKNOWN']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_person');
    }
};
