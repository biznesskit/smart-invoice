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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->string('tracking_number')->required();
            $table->string('kra_pin', 11)->unique()->required();
            $table->string('name', 60)->required();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('used_unused')->default('Y'); // Y or N
            $table->string('remark')->nullable(); 
            $table->timestamp('synced_at')->nullable(); 
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
