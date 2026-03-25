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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted

            $table->string('supplier_kra_pin', 11)->required();
            $table->string('supplier_name')->required();
            $table->string('supplier_branch_code', 2)->required();
            $table->string('supplier_invoice_number', 50)->required();   // trader_invoice_number
            $table->string('receipt_type_code', 5)->required();
            $table->string('payment_type_code', 5)->required();
            $table->string('validated_date', 19)->nullable();
            $table->string('sale_date', 8)->nullable();
            $table->string('stock_released_date',19)->nullable();
            $table->double('total_item_count')->nullable(); 
            $table->double('taxable_amount_A')->required(); 
            $table->double('taxable_amount_B')->required(); 
            $table->double('taxable_amount_C')->required(); 
            $table->double('taxable_amount_D')->required(); 
            $table->double('taxable_amount_E')->required(); 
            $table->double('tax_rate_A')->required(); 
            $table->double('tax_rate_B')->required(); 
            $table->double('tax_rate_C')->required(); 
            $table->double('tax_rate_D')->required(); 
            $table->double('tax_rate_E')->required(); 
            $table->double('tax_amount_A')->required(); 
            $table->double('tax_amount_B')->required(); 
            $table->double('tax_amount_C')->required(); 
            $table->double('tax_amount_D')->required(); 
            $table->double('tax_amount_E')->required();
            $table->double('total_taxable_amount')->required(); 
            $table->double('total_tax_amount')->required(); 
            $table->double('total_amount')->required(); 
            $table->string('remark')->nullable(); 

            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
