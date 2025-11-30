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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 8, 2);
            $table->foreignId('payment_mode_id')->constrained('payment_modes')->onDelete('restrict')->onUpdate('cascade');
            $table->string('reference_number', 100)->unique();
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
        Schema::dropIfExists('payments');
    }
};
