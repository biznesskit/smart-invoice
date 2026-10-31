<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
         
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade')->required(); // Cascade when parent is deleted

            $table->string('method');
            $table->string('CheckoutRequestID')->nullable();
            $table->string('MerchantRequestID')->nullable();
            $table->string('ResponseCode')->nullable();
            $table->string('ResponseDescription')->nullable();
            $table->string('amount')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->text('expo_device_id')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
