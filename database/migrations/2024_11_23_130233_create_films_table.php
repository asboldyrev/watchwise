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
        Schema::create('films', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('imdb_id')->nullable();
            $table->json('name');
            $table->json('rating');
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedSmallInteger('length')->nullable();
            $table->text('slogan')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->enum('type', ['FILM', 'VIDEO', 'TV_SERIES', 'MINI_SERIES', 'TV_SHOW']);
            $table->string('mpaa')->nullable();
            $table->string('age_limits')->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            $table->boolean('serial')->nullable();
            $table->boolean('short')->nullable();
            $table->boolean('completed')->nullable();
            $table->boolean('imported')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
