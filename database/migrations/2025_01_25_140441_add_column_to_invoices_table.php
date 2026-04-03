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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('current_reciept_number')->nullable();
            $table->string('total_reciept_number')->nullable();
            $table->string('internal_data')->nullable();
            $table->string('reciept_signiture')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('control_unit_date_time')->nullable();
            $table->string('control_unit_serial_number')->nullable();
            $table->string('control_unit_invoice_number')->nullable();
            $table->tinyInteger('client_webhook_delivered')->nullable();
            $table->mediumText('client_webhook_endpoint')->nullable();
            $table->tinyInteger('synced_to_etims')->nullable();
            $table->bigInteger('created_by')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
