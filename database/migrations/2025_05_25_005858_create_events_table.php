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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('artist');
            $table->string('event');
            $table->string('venue');
            $table->string('city');
            $table->timestamp('date');
            $table->string('poster');
            $table->string('info');
            $table->string('policies')->nullable();
            $table->string('spotify_iframe', 1000)->nullable();
            $table->string('venue_iframe', 1000)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
