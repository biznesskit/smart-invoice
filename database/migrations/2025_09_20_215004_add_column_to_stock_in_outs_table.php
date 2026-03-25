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
        Schema::table('stock_in_outs', function (Blueprint $table) {
             $table->string('dispatching_branch_code', 11)->nullable();
            $table->string('dispatching_branch_name', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outs', function (Blueprint $table) {
            //
        });
    }
};
