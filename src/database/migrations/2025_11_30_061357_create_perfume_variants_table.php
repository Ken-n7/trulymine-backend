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
        Schema::create('perfume_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perfume_id')->constrained('perfumes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('size_id')->constrained('perfume_sizes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('tier_id')->constrained('perfume_tiers')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('stock_quantity');
            $table->decimal('price', 8, 2);
            $table->timestampTz('created_date');
            $table->timestampTz('last_updated');
            $table->boolean('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_variants');
    }
};
