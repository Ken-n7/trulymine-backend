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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('variant_id')->constrained('perfume_variants')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('quantity');
            $table->decimal('price_at_reservation', 8, 2);
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('set null')->onUpdate('cascade');
            $table->decimal('sub_total', 8, 2);
            $table->timestampTz('created_date');
            $table->timestampTz('last_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
