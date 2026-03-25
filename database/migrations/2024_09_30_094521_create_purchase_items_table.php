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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade')->nullable();
            $table->integer('item_sequence_number')->required();
            $table->string('item_code', 20)->required();
            $table->string('item_classification_code', 10)->required();
            $table->string('item_name', 200)->required();
            $table->string('barcode', 20)->nullable();
            $table->string('packaging_unit_code', 5)->nullable();
            $table->string('packaging_unit', 5)->nullable();
            $table->string('quantity_unit_code', 5)->nullable();
            $table->double('quantity')->required();
            $table->double('unit_price')->required();

            $table->double('supply_price')->nullable();
            $table->double('discount_rate')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('total_discount_amount')->nullable();
            $table->string('insurance_code',10)->nullable();
            $table->string('insurance_name',100)->nullable();
            $table->double('insurance_amount')->nullable();
            $table->double('insurance_premium_rate')->nullable();

            $table->string('tax_type_code',5)->required();
            $table->double('taxable_amount')->required();
            $table->double('tax_amount')->required();
            $table->double('total_amount')->required();
            $table->string('supplier_item_classification_code', 10)->nullable();
            // $table->string('supplier_item_code', 20)->nullable();
            // $table->string('supplier_item_name', 200)->nullable();
            $table->string('item_expired_date', 8)->nullable();
            $table->dateTime('processed_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
