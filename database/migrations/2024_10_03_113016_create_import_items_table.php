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
        Schema::create('import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_id')->constrained('imports')->onDelete('cascade')->required();
            $table->foreignId('item_id')->nullable();
            $table->string('task_code', 50)->required();
            $table->string('declaration_date', 8)->required();
            $table->integer('item_sequence')->nullable();
            $table->string('declaration_number', 50)->nullable();
            $table->string('item_classification_code', 10)->nullable();
            $table->string('import_item_status_code', 5)->nullable();
            $table->string('item_code', 20)->nullable();
            $table->string('hs_code', 17)->nullable();
            $table->string('item_name', 500)->nullable();
            $table->string('country_of_origin', 5)->nullable();
            $table->string('export_nation_code', 5)->nullable();
            $table->string('packaging_unit_code', 5)->nullable();
            $table->string('packaging_unit', 5)->nullable();
            $table->string('quantity_unit_code', 5)->nullable();
            $table->double('quantity')->required();
            $table->string('gross_weight')->required();
            $table->string('net_weight')->required();
            $table->string('supplier_name')->required();
            $table->string('agent_name')->required();
            $table->string('remark',400)->nullable();
            $table->string('invoice_foreign_currency_code')->nullable();
            $table->string('invoice_foreign_amount')->nullable();
            $table->double('invoice_foreign_currency_exchange_rate')->nullable();
            $table->string('declaration_reference_number', 100)->nullable();
            $table->dateTime('processed_at')->nullable();


            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_items');
    }
};
