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
        Schema::create('item_composition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_composition_id')->constrained('item_compositions')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->bigInteger('item_id')->required(); 
            $table->string('item_code', 20)->required();
            $table->string('item_name')->required();
            $table->double('quantity', 13)->required();
            $table->double('remaining_quantity', 13)->required();

            $table->double('unit_price')->required(); 
            $table->double('discount_rate')->nullable();
            $table->double('discount_amount')->nullable(); 
            $table->double('total_discount_amount')->nullable(); 
            $table->string('tax_type_code')->required(); 
            $table->double('taxable_amount')->required(); 
            $table->double('tax_amount')->required();  
            $table->double('total_amount')->required();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_compositon_items');
    }
};
