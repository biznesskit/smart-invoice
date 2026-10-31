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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->string('tracking_number')->required();
            $table->string('kra_pin', 11)->unique()->required();
            $table->string('first_name', 60)->required();
            $table->string('last_name', 60)->required();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('solution_type')->nullable();
            $table->string('vat_status')->nullable();
            $table->string('etims_token')->nullable();
            $table->string('etims_device_serial_number')->nullable();
            $table->string('etims_branch_code')->nullable();
            $table->string('etims_cmc_key')->nullable();
            $table->tinyInteger( 'etims_device_initialized' )->nullable();
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
