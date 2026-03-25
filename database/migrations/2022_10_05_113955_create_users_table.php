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
        if(Schema::hasTable('users')) return;

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->required();
            $table->string('full_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->bigInteger('cashier_id')->nullable()->unique();
            $table->bigInteger('id_no')->nullable()->unique();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted
            $table->string('username',60)->required()->unique();
            $table->string('contact',20)->nullable();
            $table->string('address',200)->nullable();
            $table->string('authority_code',100)->nullable();
            $table->string('remark',2000)->nullable();
            $table->string('used_unused', 1)->default("Y");  // Y or N
            $table->timestamp('synced_at')->nullable(); 

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('expo_push_token')->nullable();
            // $table->string('image')->nullable();
            $table->integer('otp_code')->nullable()->unique();
            $table->string('kra_pin')->nullable();
            $table->double('commission_rate')->nullable();
            
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
