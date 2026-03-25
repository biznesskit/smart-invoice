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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade')->required(); // Cascade when parent is deleted

            $table->bigInteger('invoice_number')->unique()->index();
            $table->bigInteger('subscription_id')->nullable();
            $table->double('amount');
            $table->timestamp('due_date')->nullable();
            $table->double('amount_paid')->nullable();
            $table->tinyInteger('paid')->nullable();
            $table->double('balance')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
