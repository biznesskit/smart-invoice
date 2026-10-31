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
        if(Schema::hasTable('companies')) return;

          Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->required();
            $table->string('domain')->nullable();
            $table->string('business_code')->nullable()->unique();
            $table->string('slug')->required();
            $table->string('name')->required();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('kra_pin')->nullable();
            $table->string('business_type')->required();
            $table->mediumText('agent_code')->required(); // alias referal code  generated once during registration          
            $table->string('business_type_name')->nullable();
            $table->string('country_name')->nullable();
            $table->tinyInteger('subscription_active')->nullable();
            $table->tinyInteger('initial_setup_complete')->nullable();

            $table->tinyInteger('etims_active')->nullable();
            $table->tinyInteger('etims_credentials_validated')->nullable(); 
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_code')->nullable();


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
        Schema::dropIfExists('companies');
    }
};
