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
        Schema::create('stock_in_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->string('tracking_number')->required();
            $table->bigInteger('created_by')->required();
            $table->double('stored_and_released_number',38)->required();
            $table->double('original_stored_and_released_number',38)->required();
            $table->string('stored_and_released_type_code', 5)->required();
            $table->string('registration_type_code',  5)->required();
            $table->string('customer_kra_pin', 11)->nullable();
            $table->string('customer_name', 60)->nullable();
            $table->string('customer_branch_code', 2)->nullable();
            $table->string('occured_date_time', 8)->nullable();
            $table->double('total_taxable_amount',18)->required();
            $table->double('total_tax_amount',18)->required();
            $table->double('total_amount',18)->required();
            $table->string('remark',400)->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_outs');
    }
};
