<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('branches')) return;

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->string('tracking_number')->required();
            $table->string('name')->required();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('slug')->required()->unique();
            $table->string('manager_name')->nullable();
            $table->string('county_name')->nullable();
            $table->string('subcounty_name')->nullable();
            $table->string('tax_locality_name')->nullable();

            $table->string('mpesa_till_no')->nullable();
            $table->string('mpesa_paybill_no')->nullable();
            $table->string('mpesa_paybill_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->mediumText('description')->nullable();
            $table->tinyInteger('is_headquater')->nullable();
            
            $table->string('opening_hrs')->nullable();
            $table->string('string_hrs')->nullable();
            
            $table->string('kra_pin')->nullable();
            $table->string('etims_branch_code')->nullable();
            $table->string('branch_code')->unique()->nullable();
            $table->string('cmc_key')->nullable();
            $table->string('device_serial_number')->nullable();
            $table->string('branch_id')->nullable()->unique();
            $table->string('branch_status_code')->nullable();
            $table->string('scu_id')->nullable();
            $table->tinyInteger('etims_credentials_validated')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
};
