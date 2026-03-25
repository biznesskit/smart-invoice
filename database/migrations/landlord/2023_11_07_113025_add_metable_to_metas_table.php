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
        Schema::table('metas', function (Blueprint $table) {
            if (!Schema::hasColumn('metas', 'metaable_type')) {
                $table->string('metaable_type')->nullable();
                }
            if (!Schema::hasColumn('metas', 'metaable_id')) {
                $table->bigInteger('metaable_id')->nullable();
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
        Schema::table('metas', function (Blueprint $table) {
            $table->dropColumn('metaable_type');
            $table->dropColumn('metaable_id');
        });
    }
};
