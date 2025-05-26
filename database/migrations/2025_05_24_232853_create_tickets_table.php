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
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // ID alfanumérico
            $table->unsignedBigInteger('event_id');
            $table->string('section')->nullable();
            $table->string('row')->nullable();
            $table->string('seat')->nullable();
            $table->string('info');
            $table->string('category_id', 20);
            $table->integer('stock')->default(0);
            $table->foreign('category_id')->references('id')->on('ticket_categories')->onDelete('restrict');
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
