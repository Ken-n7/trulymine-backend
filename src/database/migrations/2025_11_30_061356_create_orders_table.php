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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('total_amount', 8, 2);
            $table->foreignId('status_id')->constrained('order_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('payment_status_id')->constrained('payment_statuses')->onDelete('restrict')->onUpdate('cascade');
            $table->timestampTz('created_date');
            $table->timestampTz('last_updated');
            $table->boolean('is_active');

            $table->index('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
