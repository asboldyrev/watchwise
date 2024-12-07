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
        Schema::create('persons', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->json('name');
            $table->enum('sex', ['MALE', 'FEMALE', 'UNKNOWN'])->nullable();
            $table->string('growth')->nullable();
            $table->date('birthday')->nullable();
            $table->date('death')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('death_place')->nullable();
            $table->json('spouses')->nullable();
            $table->unsignedTinyInteger('awards_count')->nullable();
            $table->string('profession')->nullable();
            $table->json('facts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
