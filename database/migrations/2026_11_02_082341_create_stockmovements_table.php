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
        Schema::create('stockmovements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Siapa yang ubah stok
            $table->enum('type', ['in', 'out', 'adjustment']); // Masuk, Keluar, atau Penyesuaian
            $table->integer('quantity'); 
            $table->string('reference_type')->nullable(); // cth: App\Models\Sale atau App\Models\Purchase
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dari Sale/Purchase
            $table->string('reason')->nullable(); // Alasan jika 'adjustment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stockmovements');
    }
};
