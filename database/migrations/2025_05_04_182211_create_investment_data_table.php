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
        Schema::create('investment_data', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique();
            $table->decimal('current_value', 10, 2);
            $table->decimal('market_variation', 10, 2);
            $table->decimal('market_variation_pct', 5, 2);
            $table->string('logo_url', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_data');
    }
};
