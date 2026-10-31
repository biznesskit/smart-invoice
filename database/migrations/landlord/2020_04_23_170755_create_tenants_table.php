<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('tracking_number')->nullable();

            $table->string('domain')->unique();  
            $table->string('kra_pin')->unique();  
            $table->string('business_type')->required();
            $table->string('country')->nullable();
            $table->string('business_email')->nullable()->unique();  
            $table->string('business_phone')->nullable()->unique();  
            $table->string('database')->required()->unique();
            $table->bigInteger('business_code')->nullable()->unique();
            $table->double('subscription_amount')->nullable();
            $table->timestamp('subscription_start_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            // $table->mediumText('reference_code')->required(); // alias referal code used to refer other users during signup. gennerated once during signup
            // $table->mediumText('referred_by')->nullable(); // refering user reference code alias referal code
            $table->mediumText('referral_code')->nullable(); // refering user reference code alias agent  code
            $table->mediumText('agent_code')->nullable(); // refering user reference code alias referal code
            $table->tinyInteger('subscription_active')->nullable();
            $table->string('database_host')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
