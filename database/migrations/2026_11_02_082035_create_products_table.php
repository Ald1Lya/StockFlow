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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('cost_price'); // Harga modal
            $table->integer('selling_price'); // Harga jual
            $table->integer('stock')->default(0); // Stok saat ini
            $table->integer('min_stock')->default(5); // Peringatan stok menipis
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
