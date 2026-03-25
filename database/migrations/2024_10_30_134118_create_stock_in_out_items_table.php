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
        Schema::create('stock_in_out_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_in_out_id')->constrained('stock_in_outs')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->bigInteger('item_id')->required();
            $table->double('item_sequence_number',3)->required(); 
            $table->string('item_code',20)->nullable(); 
            $table->string('item_classification_code', 10)->required(); 
            $table->string('item_name',  200)->required(); 
            $table->string('barcode', 20)->nullable();
            $table->double('packaging_unit', 13)->required();
            $table->string('packaging_unit_code', 5)->required();
            $table->double('quantity',13)->required();
            $table->double('remaining_quantity', 13)->required();
            $table->string('quantity_unit_code', 5)->required();
            $table->double('unit_price',15)->required(); 
            $table->double('supply_price',18)->required(); 
            $table->double('discount_rate',2)->nullable(); 
            $table->double('discount_amount',18)->nullable(); 
            $table->double('total_discount_amount',18)->nullable(); 
            $table->string('tax_type_code',5)->required();
            $table->double('taxable_amount',18)->required(); 
            $table->double('tax_amount',18)->required(); 
            $table->double('total_amount',18)->required(); 
            $table->timestamp('synced_at')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_out_items');
    }
};
