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
            $table->unsignedBigInteger('id');
            $table->json('name');
            $table->enum('sex', ['MALE', 'FEMALE'])->nullable();
            $table->string('growth')->nullable();
            $table->date('birthday')->nullable();
            $table->date('death')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('death_place')->nullable();
            // $table->string('spouses');
            $table->unsignedTinyInteger('has_awards')->nullable();
            $table->string('profession')->nullable();
            $table->array('facts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
