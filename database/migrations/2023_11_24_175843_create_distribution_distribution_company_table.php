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
        Schema::create('distribution_distribution_company', function (Blueprint $table) {
            $table->foreignId('distribution_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('distribution_company_id')->constrained(indexName: 'distribution_company_company_id_foreign')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_distribution_company');
    }
};
