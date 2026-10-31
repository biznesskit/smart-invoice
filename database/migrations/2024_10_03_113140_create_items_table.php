<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stock items, raw mateials and services
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->string('tracking_number')->required();
            $table->string('item_code', 20)->required();
            $table->string('item_classification_code', 10)->required();
            $table->string('item_type_code', 5)->required();
            $table->string('item_name', 200)->required();
            $table->string('item_standard_name', 200)->nullable();
            $table->string('country_of_origin_code', 5)->nullable();
            $table->double('packaging_unit',13)->required();
            $table->string('packaging_unit_code', 5)->nullable();
            $table->string('quantity_unit_code', 5)->nullable();
            $table->string('type')->required(); // product, service or raw material
            $table->string('tax_type_code',5)->required();
            $table->string('batch_number',10)->nullable();
            $table->string('barcode', 20)->nullable();
            $table->double('default_unit_price',18)->nullable();
            $table->double('group_1_price',18)->nullable();
            $table->double('group_2_price',18)->nullable();
            $table->double('group_3_price',18)->nullable();
            $table->double('group_4_price',18)->nullable();
            $table->double('group_5_price',18)->nullable();
            $table->string('additional_information', 7)->nullable();
            $table->string('safety_quantity', 7)->nullable();
            $table->string('insurance_applicable', 1)->nullable();
            $table->string('used_unused', 1)->nullable();  // Y or N
            $table->timestamp('synced_at')->nullable(); 
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
