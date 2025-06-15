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
       Schema::create('order_ticket', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')
          ->constrained('orders')
          ->onDelete('cascade');
    $table->string('ticket_id', 20);
    $table->foreign('ticket_id')
          ->references('id')
          ->on('tickets')
          ->onDelete('cascade');
    $table->integer('quantity')->default(1);
    $table->unique(['order_id', 'ticket_id']); // Un ticket solo una vez por orden
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_ticket');
    }
};
