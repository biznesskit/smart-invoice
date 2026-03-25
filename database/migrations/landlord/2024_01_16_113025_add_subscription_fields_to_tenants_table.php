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
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'subscription_amount')) {
                $table->string('subscription_amount')->nullable();
                }
            if (!Schema::hasColumn('tenants', 'subscription_start_date')) {
                $table->timestamp('subscription_start_date')->nullable();
                }
            if (!Schema::hasColumn('tenants', 'subscription_end_date')) {
                $table->timestamp('subscription_end_date')->nullable();
                }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('subscription_amount');
            $table->dropColumn('subscription_start_date');
            $table->dropColumn('subscription_start_end');
        });
    }
};
