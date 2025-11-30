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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_name', 100);
            $table->string('discount_code', 100)->unique();
            $table->foreignId('discount_type_id')->constrained('discount_types')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('value', 8, 2);
            $table->text('description');
            $table->timestampTz('start_date');
            $table->timestampTz('end_date');
            $table->timestampTz('created_date');
            $table->timestampTz('last_updated');
            $table->boolean('is_active');

            $table->index('discount_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
