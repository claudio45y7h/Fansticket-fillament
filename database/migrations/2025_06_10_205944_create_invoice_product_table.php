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
        Schema::create('invoice_product', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('invoice_id');
    $table->string('product_id', 20); // porque el id de producto es string
    $table->integer('quantity')->default(1);
    $table->decimal('unit_price', 10, 2);
    $table->timestamps();

    $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
    $table->unique(['invoice_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_product');
    }
};
