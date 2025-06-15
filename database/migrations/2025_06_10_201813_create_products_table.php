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
            $table->string('id', 20)->primary(); // ID alfanumérico
            $table->integer('customer_id')->nullable(); // ID del cliente, puede ser nulo
            $table->string('section')->nullable();
            $table->string('row')->nullable();
            $table->string('seat')->nullable();
            $table->string('info');
            $table->string('type');
            $table->string('gate');
            $table->integer('stock')->default(1);
            $table->decimal('price', 10, 2);
            $table->integer('barcode')->unique(); // Código de barras único
            $table->string('status')->default('transferir'); // Estado del producto

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
