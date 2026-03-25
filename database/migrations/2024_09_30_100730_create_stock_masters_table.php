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
        Schema::create('stock_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->bigInteger('item_id')->required();
            $table->foreignId('stock_in_out_id')->required(); // cascade when parent is deleted
            $table->string('item_code', 20)->required();
            $table->double('remaining_quantity',13)->required();
            $table->string('registration_name',60)->required(); 
            $table->string('modifier_id',20)->required(); 
            $table->string('modifier_name',60)->required(); 
            $table->timestamp('synced_at')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_masters');
    }
};
