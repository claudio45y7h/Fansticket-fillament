<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_responses', function (Blueprint $table) {
           
            $table->string('status'); // PAID, DECLINED, CANCELLED
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            
            // Campos comunes
            $table->string('merchant_name')->nullable();
            $table->string('receipt_no')->nullable();
            $table->string('payment_request_code')->nullable();
            $table->string('merch_inv_id')->nullable();
            $table->string('assigned_user')->nullable();
            
            // Campos para pagos aprobados
            $table->string('issuer')->nullable();
            $table->string('last4')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Campos para pagos declinados
            $table->string('status_code')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_sub_type')->nullable();
            $table->timestamp('declined_at')->nullable();
            
            // Campos para pagos cancelados
            $table->timestamp('cancelled_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_responses');
    }
};